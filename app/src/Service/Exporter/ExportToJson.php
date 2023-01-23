<?php

declare(strict_types=1);

namespace App\Service\Exporter;

use Exception;

class ExportToJson extends AbstractExporter implements ExportToFileInterface
{
    public function createOrUpdateFile(array $feeStructure): void
    {
        $config = $this->getConfig();
        if (!isset($config['feeStructureFiles']['json'])) {
            throw new Exception('Invalid configuration');
        }
        $jsonPath = $config['feeStructureFiles']['json'];
        file_put_contents($jsonPath, json_encode($feeStructure));
    }
}
