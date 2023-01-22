<?php

namespace App\DataFixtures;


use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserConnectionFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'user_connections';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
