<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use DateTimeImmutable;
use DateTimeInterface;

class CurrencyRate
{
    private Id $id;

    private Currency $currency;

    private Currency $baseCurrency;

    private string $rate;

    private DateTimeImmutable $publishedAt;

    public function __construct(
        Id $id,
        Currency $currency,
        Currency $baseCurrency,
        float $rate,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->currency = $currency;
        $this->baseCurrency = $baseCurrency;
        $this->rate = (string)$rate;
        $this->publishedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getRate(): float
    {
        return (float)$this->rate;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getBaseCurrency(): Currency
    {
        return $this->baseCurrency;
    }

    public function getPublishedAt(): DateTimeImmutable
    {
        return $this->publishedAt;
    }
}
