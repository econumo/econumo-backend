<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\UsersFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TagsFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'tags';

    public function getDependencies(): array
    {
        return [UsersFixtures::class];
    }
}
