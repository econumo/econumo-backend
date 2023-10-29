<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EnvelopeBudgetFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'envelope_budgets';

    public function getDependencies(): array
    {
        return [
            PlanFixtures::class,
            EnvelopeFixtures::class,
        ];
    }
}
