<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FolderFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'folders';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
