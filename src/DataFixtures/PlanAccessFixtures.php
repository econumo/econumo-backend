<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PlanAccessFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'plan_access';

    public function getDependencies(): array
    {
        return [UserFixtures::class, PlanFixtures::class];
    }
}
