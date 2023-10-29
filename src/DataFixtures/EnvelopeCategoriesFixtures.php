<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EnvelopeCategoriesFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'envelope_categories';

    public function getDependencies(): array
    {
        return [
            EnvelopeFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
