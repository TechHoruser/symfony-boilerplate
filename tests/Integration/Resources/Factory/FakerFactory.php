<?php

declare(strict_types=1);

namespace App\Tests\Integration\Resources\Factory;

use App\Domain\User\Entity\User;
use App\Tests\Integration\Resources\Config\FixtureValuesInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FakerFactory implements FakerFactoryInterface
{
    private Generator $faker;

    public function __construct(
        private FixtureValuesInterface $fixtureValues,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        $this->faker = Factory::create();
    }

    public function getUserPassword(): string
    {
        return $this->fixtureValues->getCommonUserPassword();
    }

    public function newUser(): User
    {
        $user = new User(
            $this->faker->uuid3(),
            $this->faker->email(),
            null,
            $this->faker->name(),
        );
        $user->setPassword($this->passwordHasher->hashPassword(
            \App\Infrastructure\Security\User::createFromUser($user),
            $this->fixtureValues->getCommonUserPassword(),
        ));
        $user->setCreatedAt(new \DateTime());
        return $user;
    }
}
