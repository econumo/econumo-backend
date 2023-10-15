<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Traits\EntityTrait;
use DateTimeImmutable;
use DateTimeInterface;

class CurrencyRate
{
    use EntityTrait;

    private string $rate;

    private DateTimeImmutable $publishedAt;

    public function __construct(
        private Id $id,
        private Currency $currency,
        private Currency $baseCurrency,
        float $rate,
        DateTimeInterface $createdAt
    ) {
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
