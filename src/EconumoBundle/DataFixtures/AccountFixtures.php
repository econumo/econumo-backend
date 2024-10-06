<?php

namespace App\EconumoBundle\DataFixtures;

use App\EconumoBundle\DataFixtures\AbstractFixture;
use App\EconumoBundle\DataFixtures\CurrencyFixtures;
use App\EconumoBundle\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AccountFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'accounts';

    public function getDependencies(): array
    {
        return [UserFixtures::class, CurrencyFixtures::class];
    }
}
