<?php

declare(strict_types=1);

namespace App\Service\Converter;

use App\Service\Converter\ConvertFeeStructureToArrayInterface;
use App\Utils\Debug;

class ConvertCsvFeeStructureToArray  implements ConvertFeeStructureToArrayInterface
{

    use Debug;

    public function convert(string $data): ?array
    {
        $arrayData = explode(PHP_EOL, $data);
        // remove headers
        unset($arrayData[0]);
        $preparedData = array_filter($arrayData, function ($value) {
            return $value !== null && $value !== PHP_EOL && $value !== '';
        });

        // prepare result
        $result = [];
        foreach ($preparedData as $row) {
            $dividedRow = explode(',', $row);
            $loan = substr($dividedRow[0], 0, strpos($dividedRow[0], ' '));
            $fee = substr($dividedRow[1], 0, strpos($dividedRow[1], ' '));
            $result[$loan] = $fee;
        }

        return $result;
    }
}
