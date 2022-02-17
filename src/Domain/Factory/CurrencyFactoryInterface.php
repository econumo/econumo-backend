<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\Currency;
use App\Domain\Entity\ValueObject\CurrencyCode;

interface CurrencyFactoryInterface
{
    public function create(CurrencyCode $code): Currency;
}
