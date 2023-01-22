<?php

declare(strict_types=1);

namespace App\Model;

/**
 * A cut down version of a loan application containing
 * only the required properties for this test.
 */
class LoanProposal
{
    private float $amount;

    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    /**
     * Amount requested for this loan application.
     */
    public function amount(): float
    {
        return $this->amount;
    }
}
