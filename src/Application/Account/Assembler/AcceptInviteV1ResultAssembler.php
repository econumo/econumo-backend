<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\AcceptInviteV1RequestDto;
use App\Application\Account\Dto\AcceptInviteV1ResultDto;
use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\Id;

class AcceptInviteV1ResultAssembler
{
    private AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler;

    public function __construct(AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler)
    {
        $this->accountToDtoV1ResultAssembler = $accountToDtoV1ResultAssembler;
    }

    public function assemble(
        AcceptInviteV1RequestDto $dto,
        Id $userId,
        Account $account
    ): AcceptInviteV1ResultDto {
        $result = new AcceptInviteV1ResultDto();
        $result->account = $this->accountToDtoV1ResultAssembler->assemble($userId, $account);

        return $result;
    }
}
