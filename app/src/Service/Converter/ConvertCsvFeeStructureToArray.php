<?php

declare(strict_types=1);

namespace App\Service\Converter;

use App\Service\Converter\ConvertFeeStructureToArrayInterface;

class ConvertCsvFeeStructureToArray  implements ConvertFeeStructureToArrayInterface
{
    public function convert(string $data): ?array
    {
        $arrayData = explode(PHP_EOL, $data);
        // remove headers
        unset($arrayData[0]);
        $preparedData = array_filter($arrayData, function ($value) {
            return $value !== null && $value !== PHP_EOL;
        });

        // prepare result
        $result = [];
        foreach ($preparedData as $row) {
            $dividedRow = explode(',', $row);
            $loan = substr($dividedRow[0], 0, strpos($dividedRow[0], ' '));
            $fee = substr($dividedRow[1], 0, strpos($dividedRow[1], ' '));
            $result[$loan] = $fee;
        }
        if (count($result) === 0) {
            return null;
        }

        return $result;
    }
}
