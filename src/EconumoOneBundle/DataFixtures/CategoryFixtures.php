<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoryFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'categories';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
