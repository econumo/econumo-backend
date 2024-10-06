<?php

namespace App\EconumoBundle\DataFixtures;

use App\EconumoBundle\DataFixtures\AbstractFixture;
use App\EconumoBundle\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoryFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'categories';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
