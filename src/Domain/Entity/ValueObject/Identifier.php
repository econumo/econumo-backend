<?php

declare(strict_types=1);

namespace App\Domain\Entity\ValueObject;

final class Identifier
{
    private string $value;

    public static function createFromEmail(Email $email): self
    {
        return new self(strtolower(trim($email->getValue())));
    }

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
