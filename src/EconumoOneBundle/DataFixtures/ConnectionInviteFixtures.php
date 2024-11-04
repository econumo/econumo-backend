<?php

namespace App\EconumoOneBundle\DataFixtures;


use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\UsersFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ConnectionInviteFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'users_connections_invites';

    public function getDependencies(): array
    {
        return [UsersFixtures::class];
    }
}
