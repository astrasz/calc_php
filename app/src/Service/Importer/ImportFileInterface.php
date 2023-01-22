<?php

declare(strict_types=1);

namespace App\Service\Importer;


interface ImportFileInterface
{
    public function getContent(string $path): string;
}
