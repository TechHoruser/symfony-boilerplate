<?php

namespace App\Tests\Integration\Resources\FixturesPhp;

use App\Application\Shared\Helper\SecurityHelperInterface;
use App\Domain\User\Entity\User;
use App\Tests\Integration\Resources\Config\FixtureValuesInterface;
use App\Tests\Integration\Resources\Factory\FakerFactoryInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Users extends AbstractFixture
{
    public const PREFIX_REFERENCE = "user-%s-%s";

    public function __construct(
        FixtureValuesInterface $fixtureValues,
        SecurityHelperInterface $securityHelper,
        FakerFactoryInterface $fakerFactory,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct($fixtureValues, $securityHelper, $fakerFactory);
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser = new User(
            Uuid::uuid4(),
            'admin@boilerplate.com',
            null,
            'Admin User',
        );
        $adminUser->setPassword(
            $this->passwordHasher->hashPassword(
                \App\Infrastructure\Security\User::createFromUser($adminUser),
                $this->fixtureValues->getCommonUserPassword(),
            )
        );
        $adminUser->setCreatedAt(new \DateTime());

        $manager->persist($adminUser);
        $manager->flush();
    }
}
