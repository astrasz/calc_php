<?php

declare(strict_types=1);

namespace App\Service\Exporter;

interface ExportToFileInterface
{
    public function createOrUpdateFile(array $data): void;
}
