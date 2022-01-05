<?php

declare(strict_types=1);


namespace App\Domain\Traits;

use App\Domain\Entity\ValueObject\ValueObjectInterface;

trait ValueObjectTrait
{
    private string $value;

    public static function validate(string $value): void
    {
    }

    public function __construct(string $value)
    {
        self::validate($value);
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

    public function isEqual(ValueObjectInterface $valueObject): bool
    {
        return strcasecmp($this->value, $valueObject->getValue()) === 0;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->value;
    }
}
