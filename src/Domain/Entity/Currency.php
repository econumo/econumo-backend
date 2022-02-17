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
    private Id $id;
    private CurrencyCode $code;
    private string $symbol;
    private DateTimeImmutable $createdAt;

    public function __construct(Id $id, CurrencyCode $code, string $symbol, DateTimeInterface $createdAt)
    {
        $this->id = $id;
        $this->code = $code;
        $this->symbol = $symbol;
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
        } catch (MissingResourceException $exception) {
            return $this->code->getValue();
        }
    }
}
