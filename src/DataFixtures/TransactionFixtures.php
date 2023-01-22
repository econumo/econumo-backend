<?php

namespace App\DataFixtures;

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
