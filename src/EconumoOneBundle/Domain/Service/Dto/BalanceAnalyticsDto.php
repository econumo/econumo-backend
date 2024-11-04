<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Dto;

use DateTimeInterface;

class BalanceAnalyticsDto
{
    public DateTimeInterface $date;

    public float $balance;
}
