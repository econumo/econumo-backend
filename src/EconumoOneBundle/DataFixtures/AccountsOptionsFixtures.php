<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\AccountsFixtures;
use App\EconumoOneBundle\DataFixtures\UsersFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AccountsOptionsFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'accounts_options';

    public function getDependencies(): array
    {
        return [UsersFixtures::class, AccountsFixtures::class];
    }
}
