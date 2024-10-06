<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Factory;


use App\EconumoBundle\Domain\Entity\Currency;
use App\EconumoBundle\Domain\Entity\ValueObject\CurrencyCode;

interface CurrencyFactoryInterface
{
    public function create(CurrencyCode $code): Currency;
}
