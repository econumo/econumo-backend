<?php

namespace App\DataFixtures;


use App\Domain\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ConnectionInviteFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'user_connections_invites';

    public function getDependencies()
    {
        return [User::class];
    }
}
