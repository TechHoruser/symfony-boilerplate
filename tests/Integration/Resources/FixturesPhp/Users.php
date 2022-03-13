<?php

namespace App\Tests\Integration\Resources\FixturesPhp;

use App\Application\Shared\Helper\SecurityHelperInterface;
use App\Domain\Tenant\Entity\Tenant;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\PermissionType;
use App\Tests\Integration\Resources\Config\FixtureValuesInterface;
use App\Tests\Integration\Resources\Factory\FakerFactoryInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Users extends AbstractFixture implements DependentFixtureInterface
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

    public function getDependencies(): array
    {
        return [];
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser = new User(
            Uuid::uuid4(),
            'admin@boilerplate.com',
            null,
            'Admin User',
        );
        $adminUser->setPassword($this->passwordHasher->hashPassword(
            \App\Infrastructure\Security\User::createFromUser($adminUser),
            $this->fixtureValues->getCommonUserPassword(),
        ));

        $manager->persist($adminUser);
        $manager->flush();
    }
}
