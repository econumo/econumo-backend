<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PayeeFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'payees';

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
