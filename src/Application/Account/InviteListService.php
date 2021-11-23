<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Dto\GetInviteListV1RequestDto;
use App\Application\Account\Dto\GetInviteListV1ResultDto;
use App\Application\Account\Assembler\GetInviteListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountAccessInviteRepositoryInterface;
use App\Domain\Repository\AccountAccessRepositoryInterface;

class InviteListService
{
    private GetInviteListV1ResultAssembler $getInviteListV1ResultAssembler;
    private AccountAccessRepositoryInterface $accountAccessRepository;
    private AccountAccessInviteRepositoryInterface $accountAccessInviteRepository;

    public function __construct(
        GetInviteListV1ResultAssembler $getInviteListV1ResultAssembler,
        AccountAccessRepositoryInterface $accountAccessRepository,
        AccountAccessInviteRepositoryInterface $accountAccessInviteRepository
    ) {
        $this->getInviteListV1ResultAssembler = $getInviteListV1ResultAssembler;
        $this->accountAccessRepository = $accountAccessRepository;
        $this->accountAccessInviteRepository = $accountAccessInviteRepository;
    }

    public function getInviteList(
        GetInviteListV1RequestDto $dto,
        Id $userId
    ): GetInviteListV1ResultDto {
        $acceptedInvites = $this->accountAccessRepository->getOwnedByUser($userId);
        $waitingInvites = $this->accountAccessInviteRepository->getUnacceptedByUser($userId);
        return $this->getInviteListV1ResultAssembler->assemble($dto, $acceptedInvites, $waitingInvites);
    }
}
