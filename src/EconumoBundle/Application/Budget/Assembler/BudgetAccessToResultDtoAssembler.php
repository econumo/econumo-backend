<?php

declare(strict_types=1);


namespace App\EconumoBundle\Application\Budget\Assembler;


use App\EconumoBundle\Application\Budget\Dto\BudgetSharedAccessResultDto;
use App\EconumoBundle\Application\User\Assembler\UserToDtoResultAssembler;
use App\EconumoBundle\Domain\Entity\BudgetAccess;

readonly class BudgetAccessToResultDtoAssembler
{
    public function __construct(private UserToDtoResultAssembler $userToDtoResultAssembler)
    {
    }

    public function assemble(BudgetAccess $budgetAccess): BudgetSharedAccessResultDto
    {
        $result = new BudgetSharedAccessResultDto();
        $result->user = $this->userToDtoResultAssembler->assemble($budgetAccess->getUser());
        $result->role = $budgetAccess->getRole()->getAlias();
        $result->isAccepted = $budgetAccess->isAccepted() ? 1 : 0;


        return $result;
    }
}
