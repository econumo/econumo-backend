<?php

namespace App\EconumoBundle\DataFixtures;

use App\EconumoBundle\DataFixtures\AbstractFixture;
use App\EconumoBundle\DataFixtures\AccountFixtures;
use App\EconumoBundle\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AccountOptionsFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'account_options';

    public function getDependencies(): array
    {
        return [UserFixtures::class, AccountFixtures::class];
    }
}
