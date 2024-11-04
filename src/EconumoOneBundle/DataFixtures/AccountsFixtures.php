<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\CurrenciesFixtures;
use App\EconumoOneBundle\DataFixtures\UsersFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AccountsFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'accounts';

    public function getDependencies(): array
    {
        return [UsersFixtures::class, CurrenciesFixtures::class];
    }
}
