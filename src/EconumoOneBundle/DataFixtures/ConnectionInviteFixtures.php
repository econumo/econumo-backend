<?php

namespace App\EconumoOneBundle\DataFixtures;


use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ConnectionInviteFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'user_connections_invites';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
