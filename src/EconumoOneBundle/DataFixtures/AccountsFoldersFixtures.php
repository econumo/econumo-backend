<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\AccountsFixtures;
use App\EconumoOneBundle\DataFixtures\FoldersFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AccountsFoldersFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'accounts_folders';

    public function getDependencies(): array
    {
        return [FoldersFixtures::class, AccountsFixtures::class];
    }
}
