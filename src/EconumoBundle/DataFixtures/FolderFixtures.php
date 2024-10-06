<?php

namespace App\EconumoBundle\DataFixtures;

use App\EconumoBundle\DataFixtures\AbstractFixture;
use App\EconumoBundle\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FolderFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'folders';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
