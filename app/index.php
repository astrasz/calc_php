<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('dispaly_errors', '1');


require_once realpath(__DIR__) . '/vendor/autoload.php';

use App\Controller\Calculator;

$calculator = new Calculator();
$calculator->execute();
