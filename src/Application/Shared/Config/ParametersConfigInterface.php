<?php

declare(strict_types=1);

namespace App\Application\Shared\Config;

interface ParametersConfigInterface
{
    public function get(string $id);
}
