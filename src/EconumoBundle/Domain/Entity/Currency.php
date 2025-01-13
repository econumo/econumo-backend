<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Entity;

use App\EconumoBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoBundle\Domain\Entity\ValueObject\DecimalNumber;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Traits\EntityTrait;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\Intl\Exception\MissingResourceException;

class Currency
{
    use EntityTrait;

    private DateTimeImmutable $createdAt;

    public function __construct(
        private Id $id,
        private CurrencyCode $code,
        private string $symbol,
        private int $fractionDigits,
        DateTimeInterface $createdAt
    ) {
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

    public function getFractionDigits(): int
    {
        return $this->fractionDigits;
    }

    public function restoreSystemFractionDigits(): void
    {
        try {
            Currencies::getName($this->code->getValue());
            $fractionDigits = Currencies::getFractionDigits($this->code->getValue());
        } catch (MissingResourceException) {
            $fractionDigits = DecimalNumber::SCALE;
        }

        $this->fractionDigits = $fractionDigits;
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
