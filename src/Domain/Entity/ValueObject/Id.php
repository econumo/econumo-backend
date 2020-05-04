<?php

declare(strict_types=1);

namespace App\Domain\Entity\ValueObject;

class Id implements \JsonSerializable
{
    /** @var string */
    private $value;

    /**
     * Id constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new \DomainException('Id value must not be empty');
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
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

    /**
     * @param Id $id
     * @return bool
     */
    public function isEqual(Id $id): bool
    {
        return $this->value === (string)$id;
    }
}
