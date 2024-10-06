<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Entity\ValueObject;

use App\EconumoBundle\Domain\Entity\ValueObject\ValueObjectInterface;
use DomainException;
use JsonSerializable;

final class BudgetEntityType implements JsonSerializable, ValueObjectInterface, \Stringable
{
    /**
     * @var int
     */
    public const ENVELOPE = 0;

    /**
     * @var int
     */
    public const CATEGORY = 1;

    /**
     * @var int
     */
    public const TAG = 2;

    /**
     * @var string[]
     */
    public const MAPPING = [
        self::ENVELOPE => 'envelope',
        self::CATEGORY => 'category',
        self::TAG => 'tag',
    ];

    private int $value;

    public static function envelope(): self
    {
        return new self(self::ENVELOPE);
    }

    public static function category(): self
    {
        return new self(self::CATEGORY);
    }

    public static function tag(): self
    {
        return new self(self::TAG);
    }

    public static function createFromAlias(string $alias): self
    {
        $index = array_search($alias, self::MAPPING, true);
        if ($index === false) {
            throw new DomainException(sprintf('BudgetEntity with alias %d not exists', $alias));
        }

        return new self((int)$index);
    }

    public function __construct(int $value)
    {
        if (!self::isValid($value)) {
            throw new DomainException(sprintf('BudgetEntity %d not exists', $value));
        }

        $this->value = $value;
    }

    public function getAlias(): string
    {
        return self::MAPPING[$this->value];
    }

    public function isEnvelope(): bool
    {
        return $this->value === self::ENVELOPE;
    }

    public function isCategory(): bool
    {
        return $this->value === self::CATEGORY;
    }

    public function isTag(): bool
    {
        return $this->value === self::TAG;
    }

    public static function isValid(int $value): bool
    {
        return in_array($value, [self::ENVELOPE, self::CATEGORY, self::TAG], true);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->value;
    }

    public function isEqual(ValueObjectInterface $valueObject): bool
    {
        return $this->value === $valueObject->getValue();
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public static function validate($value): void
    {
        if (empty($value)) {
            throw new DomainException('Value cannot be empty');
        }

        self::createFromAlias($value);
    }
}