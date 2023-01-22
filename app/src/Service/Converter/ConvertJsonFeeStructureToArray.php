<?php

declare(strict_types=1);


namespace App\Service\Converter;


use App\Service\Converter\ConvertFeeStructureToArrayInterface;


class ConvertJsonFeeStructureToArray implements ConvertFeeStructureToArrayInterface
{
    public function convert(string $data): ?array
    {

        $feeStructure = json_decode($data, true);

        return $feeStructure;
    }
}
