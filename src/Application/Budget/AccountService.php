<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\ExcludeAccountV1RequestDto;
use App\Application\Budget\Dto\ExcludeAccountV1ResultDto;
use App\Application\Budget\Assembler\ExcludeAccountV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\BudgetAccessServiceInterface;
use App\Domain\Service\Budget\BudgetServiceInterface;

readonly class AccountService
{
    public function __construct(
        private ExcludeAccountV1ResultAssembler $excludeAccountV1ResultAssembler,
        private BudgetServiceInterface $budgetService,
        private BudgetAccessServiceInterface $budgetAccessService,
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
}
