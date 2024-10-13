<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\UI\Service\Validator;

use App\EconumoOneBundle\Domain\Entity\ValueObject\ValueObjectInterface;
use Symfony\Component\Validator\Constraint;

interface ValueObjectValidationFactoryInterface
{
    public function create(string $valueObjectClassName): Constraint;
}
