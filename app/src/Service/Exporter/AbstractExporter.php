<?php


declare(strict_types=1);

namespace App\Service\Exporter;

use App\Service\AbstractService;
use App\Service\Exporter\ExportToFileInterface;


abstract class AbstractExporter extends AbstractService implements ExportToFileInterface
{
    abstract public function createorUpdateFile(array $data): void;
}
