<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use App\Utils\DataHelper;
use App\Service\Exporter\ExportToJson;
use App\Service\Importer\ImportCsvFile;
use App\Service\Importer\ImportJsonFile;
use App\Service\Converter\ConvertCsvFeeStructureToArray;
use App\Service\Converter\ConvertJsonFeeStructureToArray;

class FeeStructure extends AbstractService
{
    const FEE_STRUCTURE = [
        19000 => 380,
        13000 => 260,
        1000 => 50,
        2000 => 90,
        3000 => 90,
        4000 => 115,
        5000 => 100,
        6000 => 120,
        11000 => 220,
        7000 => 140,
        8000 => 160,
        9000 => 180,
        10000 => 200,
        12000 => 240,
        14000 => 280,
        15000 => 300,
        16000 => 320,
        18000 => 360,
        20000 => 400,
        17000 => 340,
    ];

    public function __construct(
        private ExportToJson $exportToJsonService = new ExportToJson(),
        private ConvertCsvFeeStructureToArray $csvConverter = new ConvertCsvFeeStructureToArray(),
        private ConvertJsonFeeStructureToArray $jsonConverter = new ConvertJsonFeeStructureToArray(),
        private ImportCsvFile $csvImporter = new ImportCsvFile(),
        private ImportJsonFile $jsonImporter = new ImportJsonFile()
    ) {
        parent::__construct();
    }

    public function getFeeStructure(): array
    {
        $storageType = $this->getStorageType();
        $jsonPath = $this->getConfig()['feeStructureFiles']['json'];
        $csvPath = $this->getConfig()['feeStructureFiles']['csv'];
        $feeStructure = [];
        switch ($storageType) {
            case StorageType::JSON:
                $jsonContent = $this->jsonImporter->getContent($jsonPath);
                $feeStructure = $this->jsonConverter->convert($jsonContent);
                break;
            case StorageType::CSV:
                $csvContent = $this->csvImporter->getContent($csvPath);
                $feeStructure = $this->csvConverter->convert($csvContent);
                break;
            case StorageType::UNSET:
                $feeStructure = Self::FEE_STRUCTURE;
                break;

            default:
                $feeStructure = Self::FEE_STRUCTURE;
                break;
        }
        ksort($feeStructure);
        if (!$this->isStructureValid($feeStructure)) {
            throw new Exception('Fee structure is not valid');
        }

        return $feeStructure;
    }

    public function updateFeeStructure(array $newData): void
    {
        $feeStructure = $this->getFeeStructure();
        $newStructure = $this->createNewStructure($feeStructure, $newData);

        if (!$this->isStructureValid($newStructure)) {
            throw new Exception('Fee structure is not invalid');
        }
        $this->exportToJsonService->createOrUpdateFile($newStructure);
    }

    public function createNewStructure(array $feeStructure, array $newData): array
    {
        $index = 0;
        foreach ($feeStructure as $loan => $fee) {
            if (isset($newData[$index])) {
                $feeStructure[$loan] = $newData[$index];
            }
            $index++;
        }
        return $feeStructure;
    }

    public function isStructureValid(array $feeStructure): bool
    {
        if (count($feeStructure) === 0) {
            return false;
        }

        foreach ($feeStructure as $fee) {
            if (!$fee) {
                return false;
            }
        }
        return true;
    }
}
