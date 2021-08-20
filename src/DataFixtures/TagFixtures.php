<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TagFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'tags';

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
