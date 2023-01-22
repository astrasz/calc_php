<?php

declare(strict_types=1);

namespace App\Service\Calculator;

use App\Model\LoanProposal;

interface FeeCalculatorInterface
{
    /**
     * @return float The calculated total fee.
     */
    public function calculate(LoanProposal $application): float;
}
