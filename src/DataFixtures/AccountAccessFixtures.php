<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AccountAccessFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'account_access';

    public function getDependencies()
    {
        return [UserFixtures::class, AccountFixtures::class];
    }
}
