<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AccountAccessInviteFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'account_access_invites';

    public function getDependencies()
    {
        return [UserFixtures::class, AccountFixtures::class];
    }
}
