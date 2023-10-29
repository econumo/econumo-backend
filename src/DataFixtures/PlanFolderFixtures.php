<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PlanFolderFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'plan_folders';

    public function getDependencies(): array
    {
        return [PlanFixtures::class];
    }
}
