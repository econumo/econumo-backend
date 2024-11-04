<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget;

use App\EconumoOneBundle\Application\Budget\Dto\SetLimitV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\SetLimitV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\SetLimitV1ResultAssembler;
use App\EconumoOneBundle\Application\Exception\AccessDeniedException;
use App\EconumoOneBundle\Application\Exception\ValidationException;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\BudgetLimitInvalidDateException;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetAccessServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetLimitServiceInterface;
use DateTimeImmutable;

readonly class LimitService
{
    public function __construct(
        private BudgetAccessServiceInterface $budgetAccessService,
        private SetLimitV1ResultAssembler $setLimitV1ResultAssembler,
        private BudgetLimitServiceInterface $budgetLimitService
    ) {
    }

    public function setLimit(
        SetLimitV1RequestDto $dto,
        Id $userId
    ): SetLimitV1ResultDto {
        $budgetId = new Id($dto->budgetId);
        if (!$this->budgetAccessService->canUpdateBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }
        $elementId = new Id($dto->elementId);
        $period = DateTimeImmutable::createFromFormat('Y-m-d', $dto->period);
        $amount = $dto->amount === null ? null : floatval($dto->amount);

        try {
            $this->budgetLimitService->setLimit($budgetId, $elementId, $period, $amount);
        } catch (BudgetLimitInvalidDateException $e) {
            throw new ValidationException($e->getMessage());
        }

        return $this->setLimitV1ResultAssembler->assemble();
    }
}
