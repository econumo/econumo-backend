<?php

declare(strict_types=1);

namespace App\Domain\Entity\ValueObject;

final class Identifier
{
    private string $value;

    public static function createFromEmail(Email $email): self
    {
        $hashedEmail = $email->getValue();
        for ($i = 0; $i < 500; $i++) {
            $hashedEmail = sha1($hashedEmail);
        }

        return new self($hashedEmail);
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
