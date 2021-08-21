<?php

declare(strict_types=1);

namespace App\Domain\Entity\ValueObject;

use DomainException;
use JsonSerializable;

final class TransactionType implements JsonSerializable
{
    public const EXPENSE = 0;
    public const INCOME = 1;
    public const TRANSFER = 2;

    private int $value;

    public function __construct(int $value)
    {
        if (!self::isValid($value)) {
            throw new DomainException(sprintf('TransactionType %d not exists', $value));
        }
        $this->value = $value;
    }

    public static function isValid(int $value): bool
    {
        return in_array($value, [self::INCOME, self::EXPENSE, self::TRANSFER], true);
    }

    public function getAlias(): string
    {
        switch ($this->value) {
            case self::INCOME:
                return 'income';
            case self::EXPENSE:
                return 'expense';
            case self::TRANSFER:
                return 'transfer';
        }
        throw new DomainException(sprintf('Alias for TransactionType %d not exists', $this->value));
    }

    public function isIncome(): bool
    {
        return $this->value === self::INCOME;
    }

    public function isExpense(): bool
    {
        return $this->value === self::EXPENSE;
    }

    public function isTransfer(): bool
    {
        return $this->value === self::TRANSFER;
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

    public function getValue(): int
    {
        return $this->value;
    }
}
