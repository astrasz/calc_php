<?php

declare(strict_types=1);

namespace App;

class View
{
    public function render(array $data = []): void
    {
        $params = $this->clearData($data);
        require_once("templates/index.php");
    }

    private function clearData(array $data): array
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
