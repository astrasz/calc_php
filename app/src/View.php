<?php

declare(strict_types=1);

namespace App;

use App\Utils\DataHelper;

class View
{
    use DataHelper;

    public function render(array $data = [], $method = 'getCalculator'): void
    {
        $params = $this->clearData($data);
        require_once("templates/index.php");
    }
}
