<?php

namespace App\Tests\Integration\Resources\FixturesPhp;

use App\Application\Shared\Helper\SecurityHelperInterface;
use App\Tests\Integration\Resources\Config\FixtureValuesInterface;
use App\Tests\Integration\Resources\Factory\FakerFactoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

abstract class AbstractFixture extends Fixture
{
    public function __construct(
        protected FixtureValuesInterface $fixtureValues,
        protected SecurityHelperInterface $securityHelper,
        protected FakerFactoryInterface $fakerFactory,
    ) {}
}
