<?php

namespace App\Utils;

trait DataHelper
{
    public function dump($data): void
    {
        echo '
        <div style="background-color:lightgrey; display: inline-block; padding: 0 20px; ">
        <pre>
        ';
        print_r($data);
        echo '
        </pre>
        </div>
        ';
    }

    public function clearData(array $data): array
    {
        $clearData = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $clearData[$key] = $this->clearData($value);
            } elseif (is_int($value) || is_float($value)) {
                $clearData[$key] = $value;
            } else {
                $clearData[$key] = htmlentities($value);
            }
        }

        return $clearData;
    }
}
