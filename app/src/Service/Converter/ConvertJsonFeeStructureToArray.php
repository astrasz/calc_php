<?php

declare(strict_types=1);


namespace App\Service\Converter;


use App\Service\Converter\ConvertFeeStructureToArrayInterface;


class ConvertJsonFeeStructureToArray implements ConvertFeeStructureToArrayInterface
{
    public function convert(string $data): ?array
    {
        return json_decode($data, true);
    }
}
