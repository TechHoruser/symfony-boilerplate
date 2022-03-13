<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\GetUsers;

use App\Application\Shared\Dto\Pagination\PaginationDto;
use App\Application\Shared\Mapper\Pagination\PaginationMapper;
use App\Application\Shared\Mapper\User\UserMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;

class GetUsersHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PaginationMapper $paginationMapper,
        private UserMapper $userMapper,
    ) {}

    public function __invoke(GetUsersQuery $getUsersQuery): PaginationDto
    {
        /** @var User[] $results */
        $results = $this->userRepository->complexFind(
            $getUsersQuery->paginationProperties,
            $getUsersQuery->filters,
        );

        return $this->paginationMapper->map(
            $this->userMapper->map($results),
            $this->userRepository->countAll($getUsersQuery->filters)
        );
    }
}
