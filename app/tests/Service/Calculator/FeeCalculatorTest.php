<?php

declare(strict_types=1);

use App\Exception\FeeCalculatorException;
use App\Model\LoanProposal;
use App\Service\FeeStructure;
use PHPUnit\Framework\TestCase;
use App\Service\Calculator\FeeCalculator;

final class FeeCalculatorTest extends TestCase
{
    private $feeStructure;
    private $feeStructureService;
    private $feeCalculatorService;

    public function setUp(): void
    {
        $this->feeCalculatorService = new FeeCalculator();
        $this->feeStructureService = new FeeStructure();
        $this->feeStructure = $this->feeStructureService->getFeeStructure();
    }

    public function testReturnFalseOrFeeIfLoanAlreadyExists(): void
    {
        //given
        $feeStructure = $this->feeStructure;
        $requests = [2000, 3150, 10001, 10000, 438];
        $cases = [];
        $loans = array_keys($feeStructure);

        foreach ($requests as $request) {
            if (in_array($request, $loans, true)) {
                $cases[$request] = floatval($feeStructure[$request]);
                continue;
            }
            $cases[$request] = false;
        }

        foreach ($cases as $newRequest => $expected) {
            //when
            if (!($newRequest !== null && $newRequest >= $this->feeCalculatorService::MIN_LOAN && $newRequest <= $this->feeCalculatorService::MAX_LOAN)) {
                $this->expectException(FeeCalculatorException::class);
                throw new FeeCalculatorException();
                continue;
            }

            $result = $this->feeCalculatorService->returnFalseOrFeeIfLoanALreadyExists($newRequest, $this->feeStructure);

            //then
            $this->assertSame($expected, $result);
        }
    }

    public function testCalculate(): void
    {
        //given
        $cases = [
            [
                'feeStructure' => [2000 => 100, 3000 => 150],
                'proposals' => [
                    new LoanProposal(1000),
                    new LoanProposal(2010),
                    new LoanProposal(20001)
                ],
                'expected' => [
                    round(1000 * (100 / 2000), 0, PHP_ROUND_HALF_DOWN),
                    round((150 - 100) / (3000 - 2000) * (2010 - 2000) + 100, 0, PHP_ROUND_HALF_DOWN),
                    0
                ]
            ],
            [
                'feeStructure' => [2000 => 150, 3000 => 100],
                'proposals' => [new LoanProposal(2010)],
                'expected' => [
                    ((100 - 150) / (3000 - 2000) * (2010 - 2000) + 150 + (5 - (100 - 150) / (3000 - 2000) * (2010 - 2000) + 150 % 5))
                ]
            ],
        ];


        foreach ($cases as $case) {
            //when

            $feeStructure = $case['feeStructure'];
            $proposals = $case['proposals'];
            $expected = $case['expected'];


            if (count($proposals) > 1) {
                $index = 0;
                foreach ($proposals as $proposal) {
                    $proposalAmount = $proposal->amount();

                    if (!($proposalAmount !== null && $proposalAmount >= $this->feeCalculatorService::MIN_LOAN && $proposalAmount <= $this->feeCalculatorService::MAX_LOAN)) {
                        $this->expectException(FeeCalculatorException::class);
                        throw new FeeCalculatorException();
                        continue;
                    }

                    $result = $this->feeCalculatorService->calculate($proposal, $feeStructure);

                    //then 
                    $this->assertSame($expected[$index], $result);
                    $index++;
                }
            }

            if (count($proposals) === 1) {
                $result = $this->feeCalculatorService->calculate($proposals[0], $feeStructure);

                //then
                $this->assertSame($expected[0], $result);
            }
        }
    }
}
