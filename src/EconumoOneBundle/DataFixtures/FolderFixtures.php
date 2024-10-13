<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FolderFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'folders';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
