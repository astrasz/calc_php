<?php

declare(strict_types=1);

namespace App\Service;

use App\Utils\DataHelper;
use ArrayObject;
use Exception;

require_once realpath(__DIR__) . '/StoragType.php';

abstract class AbstractService
{

    use DataHelper;

    protected array | bool $config;

    public function __construct(
        protected StorageType $storageType = StorageType::UNSET,
    ) {
        $this->config = require realpath(__DIR__) . '/../../config/config.php';
    }

    final protected function getStorageType(): StorageType
    {
        $config = $this->getConfig();
        if (!$this->isConfigValid($config)) {
            throw new Exception('Configuration is not valid');
        }
        $jsonPath = $config['feeStructureFiles']['json'];
        $csvPath = $config['feeStructureFiles']['csv'];

        if (isset($this->config['feeStructureFiles']['json']) &&  $jsonPath !== null && file_exists($jsonPath)) {
            $this->storageType = StorageType::JSON;
        } elseif (isset($this->config['feeStructureFiles']['csv']) &&  $csvPath !== null && file_exists($csvPath)) {
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

    protected function getConfig(): array | bool
    {
        return $this->config;
    }

    protected function isConfigValid(bool | array $config): bool
    {
        return  is_array($config)
            && count($config) !== 0
            && isset($config['feeStructureFiles']['json'])
            && isset($config['feeStructureFiles']['csv']);
    }
}
