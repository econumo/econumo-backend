<?php

declare(strict_types=1);


namespace App\Domain\Service\Dto;

use DateTimeInterface;

class BalanceAnalyticsDto
{
    public DateTimeInterface $date;

    public float $balance;
}
