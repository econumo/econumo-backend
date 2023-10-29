<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EnvelopeTagsFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'envelope_tags';

    public function getDependencies(): array
    {
        return [
            EnvelopeFixtures::class,
            TagFixtures::class,
        ];
    }
}
