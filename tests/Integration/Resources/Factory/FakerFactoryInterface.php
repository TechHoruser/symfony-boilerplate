<?php

declare(strict_types=1);

namespace App\Tests\Integration\Resources\Factory;

use App\Domain\User\Entity\User;

interface FakerFactoryInterface
{
    public function newUser(): User;
    public function getUserPassword(): string;
}
