<?php

namespace App\DataFixtures;


use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ConnectionInviteFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'user_connections_invites';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
