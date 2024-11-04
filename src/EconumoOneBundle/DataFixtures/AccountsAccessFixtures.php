<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\AccountsFixtures;
use App\EconumoOneBundle\DataFixtures\UsersFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AccountsAccessFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'accounts_access';

    public function getDependencies(): array
    {
        return [UsersFixtures::class, AccountsFixtures::class];
    }
}
