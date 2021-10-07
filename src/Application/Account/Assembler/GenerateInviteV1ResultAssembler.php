<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\GenerateInviteV1RequestDto;
use App\Application\Account\Dto\GenerateInviteV1ResultDto;
use App\Application\Account\Dto\InviteResultDto;
use App\Domain\Entity\AccountAccessInvite;

class GenerateInviteV1ResultAssembler
{
    public function assemble(
        GenerateInviteV1RequestDto $dto,
        AccountAccessInvite $invite
    ): GenerateInviteV1ResultDto {
        $result = new GenerateInviteV1ResultDto();
        $inviteDto = new InviteResultDto();
        $inviteDto->accountId = $dto->accountId;
        $inviteDto->role = $invite->getRole()->getAlias();
        $inviteDto->code = $invite->getCode();
        $result->invite = $inviteDto;

        return $result;
    }
}
