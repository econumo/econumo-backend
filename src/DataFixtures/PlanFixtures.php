<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PlanFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'plans';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
