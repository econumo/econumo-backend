<?php

namespace App\EconumoBundle\DataFixtures;

use App\EconumoBundle\DataFixtures\AbstractFixture;
use App\EconumoBundle\DataFixtures\CurrencyFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CurrencyRateFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'currency_rates';

    public function getDependencies(): array
    {
        return [CurrencyFixtures::class];
    }
}
