<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Application\Connection\Assembler;


use App\EconumoOneBundle\Application\Connection\Dto\AccountAccessResultDto;
use App\EconumoOneBundle\Domain\Entity\AccountAccess;

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
