<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AccountFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'accounts';

    public function getDependencies()
    {
        return [UserFixtures::class, CurrencyFixtures::class];
    }
}
