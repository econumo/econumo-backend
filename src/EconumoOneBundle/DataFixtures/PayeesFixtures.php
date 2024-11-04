<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\UsersFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PayeesFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'payees';

    public function getDependencies(): array
    {
        return [UsersFixtures::class];
    }
}
