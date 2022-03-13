<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Shared\Entity\PaginationProperties;
use App\Domain\User\Entity\User;

interface UserRepositoryInterface
{
    public function getByEmail(string $email): ?User;

    public function getByUuid(string $uuid): ?User;

    public function complexFind(
        PaginationProperties $paginationProperties,
        array $filters = [],
    ): iterable;

    public function countAll($filters = []): int;
}
