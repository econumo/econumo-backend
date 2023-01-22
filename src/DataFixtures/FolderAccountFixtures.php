<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FolderAccountFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'folder_accounts';

    public function getDependencies(): array
    {
        return [FolderFixtures::class, AccountFixtures::class];
    }
}
