<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Analytics;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Dto\BalanceAnalyticsDto;
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
