<?php

declare(strict_types=1);

namespace App\Domain\Entity\ValueObject;

use DomainException;
use JsonSerializable;

final class AccountRole implements JsonSerializable
{
    public const ADMIN = 0;
    public const USER = 1;
    public const GUEST = 2;
    public const MAPPING = [
        self::ADMIN => 'admin',
        self::USER => 'user',
        self::GUEST => 'guest',
    ];

    private int $value;

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function guest(): self
    {
        return new self(self::GUEST);
    }

    public function __construct(int $value)
    {
        if (!self::isValid($value)) {
            throw new DomainException(sprintf('AccountRole %d not exists', $value));
        }
        $this->value = $value;
    }

    public function getAlias(): string
    {
        return self::MAPPING[$this->value];
    }

    public function isAdmin(): bool
    {
        return $this->value === self::ADMIN;
    }

    public function isUser(): bool
    {
        return $this->value === self::USER;
    }

    public function isGuest(): bool
    {
        return $this->value === self::GUEST;
    }

    public static function isValid(int $value): bool
    {
        return in_array($value, [self::ADMIN, self::USER, self::GUEST], true);
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