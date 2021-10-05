<?php

declare(strict_types=1);

namespace App\Application\Account\Account\Assembler;

use App\Application\Account\Account\Dto\AddAccountV1RequestDto;
use App\Application\Account\Account\Dto\AddAccountV1ResultDto;
use App\Application\Account\Collection\Assembler\AccountToDtoV1ResultAssembler;
use App\Domain\Entity\Account;

class AddAccountV1ResultAssembler
{
    private AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler;

    public function __construct(AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler)
    {
        $this->accountToDtoV1ResultAssembler = $accountToDtoV1ResultAssembler;
    }

    public function assemble(
        AddAccountV1RequestDto $dto,
        Account $account
    ): AddAccountV1ResultDto {
        $result = new AddAccountV1ResultDto();
        $result->item = $this->accountToDtoV1ResultAssembler->assemble($account);

        return $result;
    }
}
