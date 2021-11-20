<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\AddAccountV1RequestDto;
use App\Application\Account\Dto\AddAccountV1ResultDto;
use App\Application\Account\Assembler\AccountToDtoV1ResultAssembler;
use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\Id;

class AddAccountV1ResultAssembler
{
    private AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler;

    public function __construct(AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler)
    {
        $this->accountToDtoV1ResultAssembler = $accountToDtoV1ResultAssembler;
    }

    public function assemble(
        AddAccountV1RequestDto $dto,
        Id $userId,
        Account $account
    ): AddAccountV1ResultDto {
        $result = new AddAccountV1ResultDto();
        $result->item = $this->accountToDtoV1ResultAssembler->assemble($userId, $account);

        return $result;
    }
}
