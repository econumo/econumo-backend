<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Form;

use App\Domain\Entity\ValueObject\ValueObjectInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ValueObjectValidator
{
    /**
     * @var string|ValueObjectInterface
     */
    private string $valueObject;

    private ?TranslatorInterface $translator = null;

    /**
     * @param string|ValueObjectInterface $valueObject
     */
    public function __construct(string $valueObject)
    {
        $this->valueObject = $valueObject;
    }

    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }

    public function __invoke($value, ExecutionContextInterface $context, $payload): void
    {
        try {
            $this->valueObject::validate($value);
        } catch (\DomainException $domainException) {
            if ($this->translator !== null) {
                $message = $this->translator->trans($domainException->getMessage());
            } else {
                $message = $domainException->getMessage();
            }

            $context->buildViolation($message)
                ->atPath($context->getPropertyPath())
                ->addViolation();
        }
    }
}
