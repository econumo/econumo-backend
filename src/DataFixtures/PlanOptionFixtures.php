<?php

namespace App\DataFixtures;


use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PlanOptionFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'plan_options';

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PlanFixtures::class,
        ];
    }
}
