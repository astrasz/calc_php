<?php

declare(strict_types=1);

namespace App\Service;

require_once realpath(__DIR__) . '/StoragType.php';

use App\Service\Importer\ImportCsvFile;
use App\Service\Importer\ImportJsonFile;
use App\Service\Converter\ConvertCsvFeeStructureToArray;
use App\Service\Converter\ConvertJsonFeeStructureToArray;

abstract class AbstractService
{
    protected array | bool $config;

    public function __construct(
        protected StorageType $storageType = StorageType::UNSET,
        protected ConvertCsvFeeStructureToArray $csvConverter = new ConvertCsvFeeStructureToArray,
        protected ConvertJsonFeeStructureToArray $jsonConverter = new ConvertJsonFeeStructureToArray,
        protected ImportCsvFile $csvImporter = new ImportCsvFile(),
        protected ImportJsonFile $jsonImporter = new ImportJsonFile()
    ) {
        $this->config = require_once realpath(__DIR__) . '/../../config/config.php';
    }


    final protected function getStorageType(): StorageType
    {
        if (isset($this->config['feeStructureFiles']['json']) &&  $this->config['feeStructureFiles']['json'] !== null) {
            $this->storageType = StorageType::JSON;
        } elseif (isset($this->config['feeStructureFiles']['csv']) &&  $this->config['feeStructureFiles']['csv'] !== null) {
            $this->storageType = StorageType::CSV;
        }

        return $this->storageType;
    }

    protected function roundUpNumberIfSumNotDividedByDivider(float $a, float $b, int $divider = 5): float
    {
        if (($a + $b) % $divider) {
            $modulo = ($a + $b) % $divider;
            $a += ($divider - $modulo);
        }
        return $a;
    }
}
