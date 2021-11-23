<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\GetInviteListV1RequestDto;
use App\Application\Account\Dto\GetInviteListV1ResultDto;
use App\Application\Account\Dto\InviteResultDto;
use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\AccountAccessInvite;
use App\Domain\Repository\UserRepositoryInterface;

class GetInviteListV1ResultAssembler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param GetInviteListV1RequestDto $dto
     * @param AccountAccess[] $acceptedInvites
     * @param AccountAccessInvite[] $waitingInvites
     * @return GetInviteListV1ResultDto
     */
    public function assemble(
        GetInviteListV1RequestDto $dto,
        array $acceptedInvites,
        array $waitingInvites
    ): GetInviteListV1ResultDto {
        $result = new GetInviteListV1ResultDto();

        $waitingInvitesDto = [];
        foreach ($waitingInvites as $waitingInvite) {
            $item = new InviteResultDto();
            $item->accountId = $waitingInvite->getAccountId()->getValue();
            $item->code = $waitingInvite->getCode();
            $item->role = $waitingInvite->getRole()->getAlias();
            $user = $this->userRepository->get($waitingInvite->getRecipientId());
            $item->recipientName = $user->getName();
            $item->recipientUsername = $user->getUsername();
            $waitingInvitesDto[] = $item;
        }
        $result->waiting = $waitingInvitesDto;

        $acceptedInvitesDto = [];
        foreach ($acceptedInvites as $acceptedInvite) {
            $item = new InviteResultDto();
            $item->accountId = $acceptedInvite->getAccountId()->getValue();
            $item->code = null;
            $item->role = $acceptedInvite->getRole()->getAlias();
            $user = $this->userRepository->get($acceptedInvite->getUserId());
            $item->recipientName = $user->getName();
            $item->recipientUsername = $user->getUsername();
            $acceptedInvitesDto[] = $item;
        }
        $result->accepted = $acceptedInvitesDto;

        return $result;
    }
}
