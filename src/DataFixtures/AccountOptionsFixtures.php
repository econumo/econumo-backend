<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AccountOptionsFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'account_options';

    public function getDependencies()
    {
        return [UserFixtures::class, AccountFixtures::class];
    }
}
