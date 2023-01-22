<?php

declare(strict_types=1);

namespace App\Service\Converter;

interface ConvertFeeStructureToArrayInterface
{
    /**
     * @return array|null The fee structure array or null
     */
    public function convert(string $data): ?array;
}
