<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Assembler;

use App\Domain\Entity\Account;
use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Dto\BudgetDto;

readonly class BudgetDtoAssembler
{
    public function assemble(
        Id $userId,
        Budget $budget
    ): BudgetDto {
        $excludedAccountsIds = array_map(
            fn(Account $account) => $account->getId(),
            $budget->getExcludedAccounts($userId)->toArray()
        );
        return new BudgetDto(
            $budget->getId(),
            $budget->getUser()->getId(),
            $budget->getName(),
            $budget->getStartedAt(),
            $excludedAccountsIds,
            $budget->getAccessList()->toArray()
        );
    }
}