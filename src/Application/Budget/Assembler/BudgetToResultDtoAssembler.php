<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;


use App\Application\Budget\Dto\BudgetResultDto;
use App\Domain\Entity\Account;
use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\Id;

class BudgetToResultDtoAssembler
{
    public function assemble(Id $userId, Budget $budget): BudgetResultDto
    {
        $result = new BudgetResultDto();
        $result->id = $budget->getId()->getValue();
        $result->name = $budget->getName()->getValue();
        $result->ownerUserId = $budget->getUser()->getId()->getValue();
        $result->startDate = $budget->getStartDate()->format('Y-m-d H:i:s');
        $result->createdAt = $budget->getCreatedAt()->format('Y-m-d H:i:s');
        $result->updatedAt = $budget->getUpdatedAt()->format('Y-m-d H:i:s');
        foreach ($budget->getExcludedAccounts($userId) as $account) {
            $result->excludedAccounts[] = $account->getId()->getValue();
        }

        return $result;
    }
}
