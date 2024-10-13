<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Symfony\Form\Constraints;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Infrastructure\Exception\OperationObjectLockedException;
use App\EconumoOneBundle\Infrastructure\Symfony\OperationService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OperationIdValidator extends ConstraintValidator
{
    public function __construct(private readonly OperationService $operationIdService)
    {
    }

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (empty($value)) {
            $this->context->buildViolation($constraint->formatErrorMessage)->addViolation();
            return;
        }

        try {
            if ($this->operationIdService->checkIfHandled(new Id($value))) {
                // the argument must be a string or an object implementing __toString()
                $this->context->buildViolation($constraint->errorMessage)
                    ->setParameter('{{ string }}', $value)
                    ->addViolation();
            }
        } catch (OperationObjectLockedException) {
            $this->context->buildViolation($constraint->lockedMessage)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
