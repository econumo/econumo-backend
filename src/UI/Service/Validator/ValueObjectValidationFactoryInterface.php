<?php

declare(strict_types=1);

namespace App\UI\Service\Validator;

use App\Domain\Entity\ValueObject\ValueObjectInterface;
use Symfony\Component\Validator\Constraint;

interface ValueObjectValidationFactoryInterface
{
    /**
     * @param string|ValueObjectInterface $valueObject
     */
    public function create(string $valueObject): Constraint;
}
