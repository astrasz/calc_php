<?php

declare(strict_types=1);

namespace App\Service;



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

    public function __construct()
    {
        parent::__construct();
    }

    public function getFeeStructure(): array
    {
        $storageType = $this->getStorageType();
        $feeStructure = [];
        switch ($storageType) {
            case StorageType::JSON:
                $jsonContent = $this->jsonImporter->getContent($this->config['feeStructureFiles']['json']);
                $feeStructure = $this->jsonConverter->convert($jsonContent);
                break;
            case StorageType::CSV:
                $csvContent = $this->csvImporter->getContent($this->config['feeStructureFiles']['csv']);
                $feeStructure = $this->csvConverter->convert($csvContent);
                break;
            case StorageType::UNSET:
                $feeStructure = Self::FEE_STRUCTURE;
                break;

            default:
                $feeStructure = Self::FEE_STRUCTURE;
                break;
        }

        return $feeStructure;
    }
}
