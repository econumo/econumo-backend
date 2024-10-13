<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget;

use App\EconumoOneBundle\Application\Budget\Dto\ExcludeAccountV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\ExcludeAccountV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\ExcludeAccountV1ResultAssembler;
use App\EconumoOneBundle\Application\Exception\AccessDeniedException;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetAccessServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetServiceInterface;
use App\EconumoOneBundle\Application\Budget\Dto\IncludeAccountV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\IncludeAccountV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\IncludeAccountV1ResultAssembler;

readonly class AccountService
{
    public function __construct(
        private ExcludeAccountV1ResultAssembler $excludeAccountV1ResultAssembler,
        private BudgetServiceInterface $budgetService,
        private BudgetAccessServiceInterface $budgetAccessService,
        private IncludeAccountV1ResultAssembler $includeAccountV1ResultAssembler,
    ) {
    }

    public function excludeAccount(
        ExcludeAccountV1RequestDto $dto,
        Id $userId
    ): ExcludeAccountV1ResultDto {
        $budgetId = new Id($dto->id);
        if (!$this->budgetAccessService->canUpdateBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }
        $accountId = new Id($dto->accountId);
        $budgetDto = $this->budgetService->excludeAccount($userId, $budgetId, $accountId);
        return $this->excludeAccountV1ResultAssembler->assemble($budgetDto);
    }

    public function includeAccount(
        IncludeAccountV1RequestDto $dto,
        Id $userId
    ): IncludeAccountV1ResultDto {
        $budgetId = new Id($dto->id);
        if (!$this->budgetAccessService->canUpdateBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }
        $accountId = new Id($dto->accountId);
        $budgetDto = $this->budgetService->includeAccount($userId, $budgetId, $accountId);
        return $this->includeAccountV1ResultAssembler->assemble($budgetDto);
    }
}
