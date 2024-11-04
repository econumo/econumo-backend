<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\UsersFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FoldersFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'folders';

    public function getDependencies(): array
    {
        return [UsersFixtures::class];
    }
}
