<?php

declare(strict_types=1);

// error_reporting(0);
// ini_set('dispaly_errors', 'Off');


require_once realpath(__DIR__) . '/vendor/autoload.php';

use App\Controller\Calculator;

try {
    $calculator = new Calculator();
    $calculator->execute();
} catch (Exception $e) {
    echo ('<div style="background-color:#b14459; color:#fff; max-width:50%; position:absolute; top:100px; left:50%; padding:20px 50px;transform:translateX(-50%);">
            <h2>Something went wrong :/ </h2>
            <p>' . $e->getMessage() . '</p>
        </div>'
    );
}
