<?php

namespace App\EconumoBundle\DataFixtures;

use App\EconumoBundle\DataFixtures\AbstractFixture;
use App\EconumoBundle\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PayeeFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'payees';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
