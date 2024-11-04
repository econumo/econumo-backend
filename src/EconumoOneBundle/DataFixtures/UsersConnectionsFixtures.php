<?php

namespace App\EconumoOneBundle\DataFixtures;


use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\UsersFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UsersConnectionsFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'users_connections';

    public function getDependencies(): array
    {
        return [UsersFixtures::class];
    }
}
