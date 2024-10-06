<?php

namespace App\EconumoBundle\DataFixtures;

use App\EconumoBundle\DataFixtures\AbstractFixture;
use App\EconumoBundle\DataFixtures\AccountFixtures;
use App\EconumoBundle\DataFixtures\FolderFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FolderAccountFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'folder_accounts';

    public function getDependencies(): array
    {
        return [FolderFixtures::class, AccountFixtures::class];
    }
}
