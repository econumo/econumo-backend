<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Form;

use App\Domain\Entity\ValueObject\ValueObjectInterface;
use App\UI\Service\Validator\ValueObjectValidationFactoryInterface;
use Closure;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Contracts\Translation\TranslatorInterface;

class ValueObjectValidationFactory implements ValueObjectValidationFactoryInterface
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function create(string $valueObjectClassName): Constraint
    {
        return new Callback($this->createValidator($valueObjectClassName));
    }

    private function createValidator(string $valueObject): Closure
    {
        $validator = new ValueObjectValidator($valueObject);
        $validator->setTranslator($this->translator);
        return Closure::fromCallable($validator);
    }
}
