<?php

declare(strict_types=1);

namespace App\UI\Service\Validator;

use App\Domain\Entity\ValueObject\ValueObjectInterface;
use Symfony\Component\Validator\Constraint;

interface ValueObjectValidationFactoryInterface
{
    public function create(string $valueObjectClassName): Constraint;
}
