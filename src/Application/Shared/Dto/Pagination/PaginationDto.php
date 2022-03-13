<?php

declare(strict_types=1);

namespace App\Application\Shared\Dto\Pagination;

class PaginationDto
{
    public function __construct(
        readonly array $results,
        readonly int $totalItems,
    ) {}
}
