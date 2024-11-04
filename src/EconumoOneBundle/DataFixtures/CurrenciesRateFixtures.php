<?php

namespace App\EconumoOneBundle\DataFixtures;

use App\EconumoOneBundle\DataFixtures\AbstractFixture;
use App\EconumoOneBundle\DataFixtures\CurrenciesFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CurrenciesRateFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'currencies_rates';

    public function getDependencies(): array
    {
        return [CurrenciesFixtures::class];
    }
}
