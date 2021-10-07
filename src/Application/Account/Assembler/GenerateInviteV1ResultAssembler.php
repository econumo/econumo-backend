<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\GenerateInviteV1RequestDto;
use App\Application\Account\Dto\GenerateInviteV1ResultDto;
use App\Application\Account\Dto\InviteResultDto;
use App\Domain\Entity\AccountAccessInvite;
use App\Domain\Repository\UserRepositoryInterface;

class GenerateInviteV1ResultAssembler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function assemble(
        GenerateInviteV1RequestDto $dto,
        AccountAccessInvite $invite
    ): GenerateInviteV1ResultDto {
        $result = new GenerateInviteV1ResultDto();
        $user = $this->userRepository->get($invite->getRecipientId());
        $inviteDto = new InviteResultDto();
        $inviteDto->accountId = $dto->accountId;
        $inviteDto->role = $invite->getRole()->getAlias();
        $inviteDto->code = $invite->getCode();
        $inviteDto->recipientUsername = $dto->recipientUsername;
        $inviteDto->recipientName = $user->getName();
        $result->invite = $inviteDto;

        return $result;
    }
}
