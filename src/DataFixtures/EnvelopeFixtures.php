<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EnvelopeFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'envelopes';

    public function getDependencies(): array
    {
        return [
            PlanFixtures::class,
            CurrencyFixtures::class,
            PlanFolderFixtures::class
        ];
    }
}
