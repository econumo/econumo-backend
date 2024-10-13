<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\AccountFixtures;
use App\EconumoOneBundle\DataFixtures\FolderFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FolderAccountFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'folder_accounts';

    public function getDependencies(): array
    {
        return [FolderFixtures::class, AccountFixtures::class];
    }
}
