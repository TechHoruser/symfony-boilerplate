<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\Pagination;

use App\Application\Shared\Dto\Pagination\PaginationDto;

class PaginationMapper
{
    /**
     * @param array $results
     * @param int|null $totalItems
     * @return PaginationDto
     */
    public function map($results, ?int $totalItems = null): PaginationDto
    {
        return new PaginationDto(
            $results,
            $totalItems ?? count($results)
        );
    }
}
