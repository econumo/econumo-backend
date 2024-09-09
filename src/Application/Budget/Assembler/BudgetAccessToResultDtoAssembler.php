<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;


use App\Application\Budget\Dto\BudgetAccessResultDto;
use App\Application\User\Assembler\UserToDtoResultAssembler;
use App\Domain\Entity\BudgetAccess;

readonly class BudgetAccessToResultDtoAssembler
{
    public function __construct(private UserToDtoResultAssembler $userToDtoResultAssembler)
    {
    }

    public function assemble(BudgetAccess $budgetAccess): BudgetAccessResultDto
    {
        $result = new BudgetAccessResultDto();
        $result->user = $this->userToDtoResultAssembler->assemble($budgetAccess->getUser());
        $result->role = $budgetAccess->getRole()->getAlias();
        $result->isAccepted = $budgetAccess->isAccepted() ? 1 : 0;


        return $result;
    }
}
