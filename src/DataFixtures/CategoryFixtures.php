<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoryFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'categories';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
