<?php

declare(strict_types=1);


namespace App\EconumoBundle\Application\Budget\Assembler;


use App\EconumoBundle\Application\Budget\Dto\BudgetSharedAccessResultDto;
use App\EconumoBundle\Application\User\Dto\UserResultDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetUserAccessDto;

readonly class BudgetAccessToResultDtoAssembler
{
    public function assemble(BudgetUserAccessDto $budgetUserAccess): BudgetSharedAccessResultDto
    {
        $result = new BudgetSharedAccessResultDto();
        $result->user = new UserResultDto();
        $result->user->id = $budgetUserAccess->id->getValue();
        $result->user->name = $budgetUserAccess->name->getValue();
        $result->user->avatar = $budgetUserAccess->avatar;
        $result->role = $budgetUserAccess->role->getAlias();
        $result->isAccepted = $budgetUserAccess->isAccepted ? 1 : 0;


        return $result;
    }
}
