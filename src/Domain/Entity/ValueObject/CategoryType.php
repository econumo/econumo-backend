<?php

declare(strict_types=1);

namespace App\Domain\Entity\ValueObject;

use DomainException;
use JsonSerializable;

final class CategoryType implements JsonSerializable
{
    public const EXPENSE = 0;
    public const INCOME = 1;
    public const EXPENSE_ALIAS = 'expense';
    public const INCOME_ALIAS = 'income';
    private const MAPPING = [
        self::EXPENSE_ALIAS => self::EXPENSE,
        self::INCOME_ALIAS => self::INCOME
    ];

    private int $value;

    public static function createFromAlias(string $alias): self
    {
        $alias = strtolower(trim($alias));
        if (!array_key_exists($alias, self::MAPPING)) {
            throw new DomainException(sprintf('CategoryType %d not exists', $alias));
        }
        return new self(self::MAPPING[$alias]);
    }

    public function __construct(int $value)
    {
        if (!self::isValid($value)) {
            throw new DomainException(sprintf('CategoryType %d not exists', $value));
        }
        $this->value = $value;
    }

    public static function isValid(int $value): bool
    {
        return in_array($value, [self::INCOME, self::EXPENSE], true);
    }

    public function getAlias(): string
    {
        switch ($this->value) {
            case self::INCOME:
                return 'income';
            case self::EXPENSE:
                return 'expense';
        }
        throw new DomainException(sprintf('Alias for CategoryType %d not exists', $this->value));
    }

    public function isIncome(): bool
    {
        return $this->value === self::INCOME;
    }

    public function isExpense(): bool
    {
        return $this->value === self::EXPENSE;
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

    public function __toString()
    {
        return (string)$this->value;
    }
}
