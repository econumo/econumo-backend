<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Entity\ValueObject;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Email;

final readonly class Identifier implements \Stringable
{
    public static function createFromEmail(Email $email): self
    {
        return new self(strtolower(trim($email->getValue())));
    }

    public function __construct(private string $value)
    {
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
