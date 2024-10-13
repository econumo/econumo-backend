<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PayeeFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'payees';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
