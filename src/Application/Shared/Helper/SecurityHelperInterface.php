<?php

declare(strict_types=1);

namespace App\Application\Shared\Helper;

interface SecurityHelperInterface
{
    public function encryptString(string $data): string;
    public function decryptString(string $data): string;
}
