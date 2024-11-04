<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\Currency;
use App\EconumoOneBundle\Domain\Entity\ValueObject\CurrencyCode;

interface CurrencyFactoryInterface
{
    public function create(CurrencyCode $code): Currency;
}
