<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\CurrencyCode;

class CurrencyDto
{
    public CurrencyCode $code;

    public string $symbol;
}
