<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Trait\AuditableEntityTrait;
use Ramsey\Uuid\Uuid;

class User
{
    use AuditableEntityTrait;

    protected string $uuid;

    public function __construct(
        ?string $uuid,
        protected string $email,
        protected ?string $password,
        protected string $name,
    )
    {
        $this->uuid = $uuid ?? Uuid::uuid4()->toString();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
