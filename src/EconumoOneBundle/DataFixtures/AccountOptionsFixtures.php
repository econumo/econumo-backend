<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\AccountFixtures;
use App\EconumoOneBundle\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AccountOptionsFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'account_options';

    public function getDependencies(): array
    {
        return [UserFixtures::class, AccountFixtures::class];
    }
}
