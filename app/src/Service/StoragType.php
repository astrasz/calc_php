<?php

declare(strict_types=1);

namespace App\Service;

enum StorageType: string
{
    case UNSET = '';
    case JSON = 'json';
    case CSV = 'csv';
}
