<?php

declare(strict_types=1);


namespace App\Domain\Service\Analytics;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Dto\BalanceAnalyticsDto;
use DateTimeInterface;

interface BalanceAnalyticsServiceInterface
{
    /**
     * @param DateTimeInterface $from
     * @param DateTimeInterface $to
     * @param Id $userId
     * @return BalanceAnalyticsDto[]
     */
    public function getBalanceAnalytics(DateTimeInterface $from, DateTimeInterface $to, Id $userId): array;
}
