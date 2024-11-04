<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\CurrencyCode;
use DateTimeInterface;

class CurrencyRateDto
{
    public CurrencyCode $code;

    public CurrencyCode $base;

    public float $rate;

    public DateTimeInterface $date;
}
