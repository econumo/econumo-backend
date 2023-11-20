<?php

namespace App\DataFixtures;


use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserPasswordRequestFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'user_password_requests';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
