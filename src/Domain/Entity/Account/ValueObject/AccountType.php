<?php

declare(strict_types=1);

namespace App\Domain\Entity\Account\ValueObject;

use DomainException;
use JsonSerializable;

final class AccountType implements JsonSerializable
{
    public const CASH = 1;
    public const CREDIT_CARD = 2;

    /**
     * @var string
     */
    private $value;

    public function __construct(int $value)
    {
        if (!static::isValid($value)) {
            throw new DomainException(sprintf('AccountType %d not exists', $value));
        }
        $this->value = $value;
    }

    public static function isValid(int $value): bool
    {
        return in_array($value, [static::CASH, static::CREDIT_CARD], true);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->value;
    }

    public function isEqual(self $valueObject): bool
    {
        return $this->value === $valueObject->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
