<?php

namespace App\Infrastructure\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class User extends \App\Domain\User\Entity\User implements JWTUserInterface, PasswordAuthenticatedUserInterface
{
    public static function createFromUser(\App\Domain\User\Entity\User $user): static
    {
        return new static(
            $user->getUuid(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getName(),
        );
    }

    public static function createFromPayload($username, array $payload): static
    {
        return new static(
            $payload['uuid'],
            $payload['email'],
            null,
            null,
        );
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getUuid();
    }

    public function getUsername(): string
    {
        return $this->getEmail();
    }

}
