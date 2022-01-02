<?php

declare(strict_types=1);

namespace App\Domain\Entity\ValueObject;

use DomainException;
use JsonSerializable;

class Icon implements JsonSerializable
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new DomainException('Icon value must not be empty');
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
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
}
