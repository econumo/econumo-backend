<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\AccountsFixtures;
use App\EconumoOneBundle\DataFixtures\CategoriesFixtures;
use App\EconumoOneBundle\DataFixtures\PayeesFixtures;
use App\EconumoOneBundle\DataFixtures\TagsFixtures;
use App\EconumoOneBundle\DataFixtures\UsersFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TransactionsFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'transactions';

    public function getDependencies(): array
    {
        return [
            UsersFixtures::class,
            AccountsFixtures::class,
            CategoriesFixtures::class,
            TagsFixtures::class,
            PayeesFixtures::class
        ];
    }
}
