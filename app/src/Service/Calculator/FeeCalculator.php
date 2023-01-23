<?php

declare(strict_types=1);

namespace App\Service\Calculator;

use App\Exception\FeeCalculatorException;
use App\Utils\DataHelper;
use App\Model\LoanProposal;
use App\Service\FeeStructure;
use App\Service\AbstractService;
use App\Service\Calculator\FeeCalculatorInterface;

class FeeCalculator extends AbstractService implements FeeCalculatorInterface
{
    use DataHelper;

    const MIN_LOAN = 1000;
    const MAX_LOAN = 20000;

    public function __construct(private FeeStructure $feeStructureService = new FeeStructure())
    {
        parent::__construct();
    }

    public function calculate(LoanProposal $application, array $feeStructure): float
    {
        // fetch data
        $newRequest = $application->amount();
        if (!$this->isRequestValid($newRequest)) {
            throw new FeeCalculatorException('Proposed loan is not valid');
        }

        // return fee if same loan already exists
        if ($fee = $this->returnFalseOrFeeIfLoanAlreadyExists($newRequest, $feeStructure)) {
            return $fee;
        };

        // calculate fee if loan is the biggest or the smallest one
        list($smallerLoanKey, $biggerLoanKey, $loans) = $this->getBoundaryLoansKeysFromFeeStructure($newRequest, $feeStructure);
        if ($smallerLoanKey === null) {
            return $this->calculateFeeForBoundaryLoan($newRequest, $biggerLoanKey, $loans, $feeStructure);
        }
        if ($biggerLoanKey === null) {
            return $this->calculateFeeForBoundaryLoan($newRequest, $smallerLoanKey, $loans, $feeStructure);
        }

        // calculate fee if proposed loan is in structure range
        $feeForNewRequest = $this->calculateFeeForLoanInStructureRange($feeStructure, $loans, $smallerLoanKey, $biggerLoanKey, $newRequest);

        // round fee up if proposed loan + fee is not divided by 5 
        return $this->roundUpNumberIfSumNotDividedByDivider($feeForNewRequest, $newRequest);
    }

    public function returnFalseOrFeeIfLoanAlreadyExists(float $newRequest, array $feeStructure): float | false
    {
        if (!$this->isRequestValid($newRequest)) {
            throw new FeeCalculatorException('Proposed loan is not valid');
        }

        $sameLoanKey = array_key_exists($newRequest, $feeStructure);
        if ($sameLoanKey) {
            return floatval($feeStructure[$newRequest]);
        }
        return false;
    }

    private function getBoundaryLoansKeysFromFeeStructure(float $newRequest, array $feeStructure): array
    {
        // add proposed loan and sort array by keys
        $feeStructure[$newRequest] = 0;
        ksort($feeStructure);

        // get boundary keys
        $loans = array_keys($feeStructure);
        $proposedLoanKey = array_search($newRequest, $loans);
        if ($proposedLoanKey === false) {
            throw new FeeCalculatorException('Proposal loan does not exist in the fee structure');
        }

        $smallerLoanKey = $proposedLoanKey === 0 ? null : $proposedLoanKey - 1;
        $biggerLoanKey = $proposedLoanKey === count($feeStructure) - 1 ? null : $proposedLoanKey + 1;
        if ($smallerLoanKey === null && $biggerLoanKey === null) {
            throw new FeeCalculatorException('Cannot calculate fee without the fee structure');
        }

        return [$smallerLoanKey, $biggerLoanKey, $loans];
    }

    private function calculateFeeForBoundaryLoan(float $newLoan, int $nearestLoanFromStructureKey, array $loansArray, array $feeStructure): float
    {
        $nearestLoanFromStructure = $loansArray[$nearestLoanFromStructureKey];

        $feeFromStructure = $feeStructure[$nearestLoanFromStructure];

        $feeForUnit = $feeFromStructure / $nearestLoanFromStructure;

        return round($newLoan * $feeForUnit, 0, PHP_ROUND_HALF_DOWN);
    }

    private function calculateFeeForLoanInStructureRange(array $feeStructure, array $loansArray, int $smallerLoanKey, int $biggerLoanKey, float $newLoan): float
    {
        $biggerLoan = $loansArray[$biggerLoanKey];
        $feeForBiggerLoan = $feeStructure[$biggerLoan];
        $smallerLoan = $loansArray[$smallerLoanKey];
        $feeForSmallerLoan = $feeStructure[$smallerLoan];

        $loansDiffrence = $biggerLoan - $smallerLoan;
        $feesDiffrence = $feeForBiggerLoan - $feeForSmallerLoan;

        $feeForUnit = $feesDiffrence / $loansDiffrence;

        return round($feeForSmallerLoan + ($newLoan - $smallerLoan) * $feeForUnit, 0, PHP_ROUND_HALF_DOWN);
    }

    private function isRequestValid(float $newRequest): bool
    {
        return $newRequest !== null && $newRequest >= self::MIN_LOAN && $newRequest <= self::MAX_LOAN;
    }
}
