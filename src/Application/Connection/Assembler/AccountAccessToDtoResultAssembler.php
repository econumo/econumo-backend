<?php

declare(strict_types=1);


namespace App\Application\Connection\Assembler;


use App\Application\Connection\Dto\AccountAccessResultDto;
use App\Domain\Entity\AccountAccess;

class AccountAccessToDtoResultAssembler
{
    public function assemble(AccountAccess $accountAccess): AccountAccessResultDto
    {
        $accountAccessDto = new AccountAccessResultDto();
        $accountAccessDto->id = $accountAccess->getAccountId()->getValue();
        $accountAccessDto->ownerUserId = $accountAccess->getAccount()->getUserId()->getValue();
        $accountAccessDto->role = $accountAccess->getRole()->getAlias();

        return $accountAccessDto;
    }
}
