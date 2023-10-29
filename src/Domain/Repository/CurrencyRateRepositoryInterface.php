<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\CurrencyRate;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface CurrencyRateRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function get(Id $currencyId, DateTimeInterface $date): CurrencyRate;

    public function getLatest(Id $currencyId, ?DateTimeInterface $date = null): CurrencyRate;

    /**
     * @return CurrencyRate[]
     */
    public function getAll(?DateTimeInterface $date = null): array;

    /**
     * @param CurrencyRate[] $items
     */
    public function save(array $items): void;

    /**
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     * @param Id $baseCurrencyId
     * @return array
     */
    public function getAverage(DateTimeInterface $startDate, DateTimeInterface $endDate, Id $baseCurrencyId): array;
}
