<?php

declare(strict_types=1);

namespace App\Service\Calculator;

use Exception;
use App\Utils\Debug;
use App\Model\LoanProposal;
use App\Service\AbstractService;
use App\Service\Calculator\FeeCalculatorInterface;
use App\Service\FeeStructure;

class FeeCalculator extends AbstractService implements FeeCalculatorInterface
{
    use Debug;

    public function __construct(private FeeStructure $feeStructureService = new FeeStructure())
    {
        parent::__construct();
    }

    public function calculate(LoanProposal $application): float
    {
        // fetch data
        $newRequest = $application->amount();
        $feeStructure = $this->feeStructureService->getFeeStructure();

        if (!$newRequest || $newRequest === 0) {
            throw new Exception('Proposed loan is invalid');
        }

        if (!$newRequest || count($feeStructure) === 0) {
            throw new Exception('Fee structure does not exist');
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
        $sameLoanKey = array_key_exists($newRequest, $feeStructure);
        if ($sameLoanKey) {
            return $feeStructure[$newRequest];
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
        if (!$proposedLoanKey) {
            throw new Exception('Proposal loan does not exist in the fee structure');
        }

        $smallerLoanKey = $proposedLoanKey === 0 ? null : $proposedLoanKey - 1;
        $biggerLoanKey = $proposedLoanKey === count($feeStructure) - 1 ? null : $proposedLoanKey + 1;
        if ($smallerLoanKey === null && $biggerLoanKey === null) {
            throw new Exception('Cannot calculate fee without the fee structure');
        }

        return [$smallerLoanKey, $biggerLoanKey, $loans];
    }

    private function calculateFeeForBoundaryLoan(float $newLoan, int $nearestLoanFromStructureKey, array $loansArray, array $feeStructure): float
    {
        $nearestLoanFromStructure = $loansArray[$nearestLoanFromStructureKey];
        $feeFromStructure = $feeStructure[$nearestLoanFromStructure];

        $feeForUnit = $nearestLoanFromStructure / $feeFromStructure;

        return $newLoan * $feeForUnit;
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
}
