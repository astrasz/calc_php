<?php

declare(strict_types=1);

namespace App\Service\Importer;

use App\Service\Importer\ImportFileInterface;
use Exception;

class ImportCsvFile implements ImportFileInterface
{
    public function getContent(string $path): string
    {

        $content = file_get_contents($path);

        if (!$content) {
            throw new Exception('Cannot import csv file');
        }

        return $content;
    }
}
