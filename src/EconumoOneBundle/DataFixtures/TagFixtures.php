<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TagFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'tags';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
