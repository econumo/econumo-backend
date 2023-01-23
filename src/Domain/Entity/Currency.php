<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Entity\ValueObject\Id;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\Intl\Exception\MissingResourceException;

class Currency
{
    private DateTimeImmutable $createdAt;

    public function __construct(private Id $id, private CurrencyCode $code, private string $symbol, DateTimeInterface $createdAt)
    {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getCode(): CurrencyCode
    {
        return $this->code;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getName(): string
    {
        try {
            return Currencies::getName($this->code->getValue());
        } catch (MissingResourceException) {
            return $this->code->getValue();
        }
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
