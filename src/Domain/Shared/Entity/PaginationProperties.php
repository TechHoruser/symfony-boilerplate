<?php

declare(strict_types=1);

namespace App\Domain\Shared\Entity;

class PaginationProperties
{
    public function __construct(
        readonly int $page = 0,
        readonly int $resultsPerPage = 0,
        readonly ?string $sortBy = null,
        readonly ?string $sortOrder = null,
    ) {}
}
