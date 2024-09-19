<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Assembler;

use App\Domain\Entity\Account;
use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Dto\BudgetPreviewDto;

readonly class BudgetPreviewDtoAssembler
{
    public function assemble(
        Id $userId,
        Budget $budget
    ): BudgetPreviewDto {
        $excludedAccountsIds = array_map(
            fn(Account $account) => $account->getId(),
            $budget->getExcludedAccounts($userId)->toArray()
        );
        return new BudgetPreviewDto(
            $budget->getId(),
            $budget->getUser()->getId(),
            $budget->getName(),
            $budget->getStartedAt(),
            $excludedAccountsIds,
            $budget->getAccessList()->toArray()
        );
    }
}