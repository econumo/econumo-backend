<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\CurrencyFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CurrencyRateFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'currency_rates';

    public function getDependencies(): array
    {
        return [CurrencyFixtures::class];
    }
}
