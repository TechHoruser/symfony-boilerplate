<?php

declare(strict_types=1);

namespace App\Application\Shared\Dto\User;

class UserDto
{
    public function __construct(
        readonly ?string $uuid,
        readonly string $email,
    ) {}
}
