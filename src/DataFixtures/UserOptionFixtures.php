<?php

namespace App\DataFixtures;


use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserOptionFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'user_options';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
