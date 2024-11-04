<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Symfony\Form;

use App\EconumoOneBundle\Domain\Entity\ValueObject\ValueObjectInterface;
use App\EconumoOneBundle\Infrastructure\Symfony\Form\ValueObjectValidator;
use App\EconumoOneBundle\UI\Service\Validator\ValueObjectValidationFactoryInterface;
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
