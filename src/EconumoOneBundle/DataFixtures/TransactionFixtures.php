<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\AccountFixtures;
use App\EconumoOneBundle\DataFixtures\CategoryFixtures;
use App\EconumoOneBundle\DataFixtures\PayeeFixtures;
use App\EconumoOneBundle\DataFixtures\TagFixtures;
use App\EconumoOneBundle\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TransactionFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'transactions';

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            AccountFixtures::class,
            CategoryFixtures::class,
            TagFixtures::class,
            PayeeFixtures::class
        ];
    }
}
