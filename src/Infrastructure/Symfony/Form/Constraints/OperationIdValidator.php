<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Form\Constraints;

use App\Domain\Entity\ValueObject\Id;
use App\Infrastructure\Exception\OperationObjectLockedException;
use App\Infrastructure\Symfony\OperationService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OperationIdValidator extends ConstraintValidator
{
    private OperationService $operationIdService;

    public function __construct(OperationService $operationIdService)
    {
        $this->operationIdService = $operationIdService;
    }

    /**
     * @param mixed $value
     * @param Constraint|OperationId $constraint
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
        } catch (OperationObjectLockedException $operationObjectLockedException) {
            $this->context->buildViolation($constraint->lockedMessage)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
