<?php

namespace App\EconumoBundle\DataFixtures;

use App\EconumoBundle\DataFixtures\AbstractFixture;
use App\EconumoBundle\DataFixtures\AccountFixtures;
use App\EconumoBundle\DataFixtures\CategoryFixtures;
use App\EconumoBundle\DataFixtures\PayeeFixtures;
use App\EconumoBundle\DataFixtures\TagFixtures;
use App\EconumoBundle\DataFixtures\UserFixtures;
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
