<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\User;

use App\Application\Shared\Dto\User\UserDto;
use App\Domain\User\Entity\User;

class UserMapper
{
    /**
     * @param User[] $users
     * @return UserDto[]
     */
    public function map($users): iterable
    {
        $usersDto = [];

        foreach ($users as $user) {
            $usersDto[] = new UserDto(
                $user->getUuid(),
                $user->getEmail()
            );
        }

        return $usersDto;
    }
}
