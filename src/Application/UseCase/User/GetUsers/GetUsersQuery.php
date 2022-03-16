<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\GetUsers;

use App\Application\Shared\Query\QueryInterface;
use App\Domain\Shared\Entity\PaginationProperties;

class GetUsersQuery implements QueryInterface
{
    public function __construct(
        readonly PaginationProperties $paginationProperties,
        readonly array $filters,
    ) {}
}
