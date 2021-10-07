<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Assembler\AcceptInviteV1ResultAssembler;
use App\Application\Account\Assembler\GenerateInviteV1ResultAssembler;
use App\Application\Account\Dto\AcceptInviteV1RequestDto;
use App\Application\Account\Dto\AcceptInviteV1ResultDto;
use App\Application\Account\Dto\GenerateInviteV1RequestDto;
use App\Application\Account\Dto\GenerateInviteV1ResultDto;
use App\Application\Account\Dto\GetInviteV1RequestDto;
use App\Application\Account\Dto\GetInviteV1ResultDto;
use App\Application\Account\Assembler\GetInviteV1ResultAssembler;
use App\Domain\Entity\ValueObject\AccountRole;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountAccessInviteRepositoryInterface;
use App\Domain\Repository\AccountAccessRepositoryInterface;
use App\Domain\Service\AccountAccessInviteServiceInterface;
use App\Domain\Service\AccountAccessServiceInterface;

class InviteService
{
    private GenerateInviteV1ResultAssembler $generateInviteV1ResultAssembler;
    private AccountAccessInviteServiceInterface $accountAccessInviteService;
    private AccountAccessServiceInterface $accountAccessService;
    private AcceptInviteV1ResultAssembler $acceptInviteV1ResultAssembler;
    private GetInviteV1ResultAssembler $getInviteV1ResultAssembler;
    private AccountAccessRepositoryInterface $accountAccessRepository;
    private AccountAccessInviteRepositoryInterface $accountAccessInviteRepository;

    public function __construct(
        GenerateInviteV1ResultAssembler $generateInviteV1ResultAssembler,
        AccountAccessInviteServiceInterface $accountAccessInviteService,
        AccountAccessServiceInterface $accountAccessService,
        AcceptInviteV1ResultAssembler $acceptInviteV1ResultAssembler,
        GetInviteV1ResultAssembler $getInviteV1ResultAssembler,
        AccountAccessRepositoryInterface $accountAccessRepository,
        AccountAccessInviteRepositoryInterface $accountAccessInviteRepository
    ) {
        $this->generateInviteV1ResultAssembler = $generateInviteV1ResultAssembler;
        $this->accountAccessInviteService = $accountAccessInviteService;
        $this->accountAccessService = $accountAccessService;
        $this->acceptInviteV1ResultAssembler = $acceptInviteV1ResultAssembler;
        $this->getInviteV1ResultAssembler = $getInviteV1ResultAssembler;
        $this->accountAccessRepository = $accountAccessRepository;
        $this->accountAccessInviteRepository = $accountAccessInviteRepository;
    }

    public function generateInvite(
        GenerateInviteV1RequestDto $dto,
        Id $userId
    ): GenerateInviteV1ResultDto {
        $this->accountAccessService->checkGenerateInviteAccess($userId, new Id($dto->accountId));
        $invite = $this->accountAccessInviteService->generate(
            $userId,
            new Id($dto->accountId),
            new Email($dto->recipientUsername),
            AccountRole::createFromAlias($dto->role)
        );
        return $this->generateInviteV1ResultAssembler->assemble($dto, $invite);
    }

    public function acceptInvite(
        AcceptInviteV1RequestDto $dto,
        Id $userId
    ): AcceptInviteV1ResultDto {
        $account = $this->accountAccessInviteService->accept($userId, $dto->code);
        return $this->acceptInviteV1ResultAssembler->assemble($dto, $userId, $account);
    }

    public function getInvite(
        GetInviteV1RequestDto $dto,
        Id $userId
    ): GetInviteV1ResultDto {
        $acceptedInvites = $this->accountAccessRepository->getOwnedByUser($userId);
        $waitingInvites = $this->accountAccessInviteRepository->getUnacceptedByUser($userId);
        return $this->getInviteV1ResultAssembler->assemble($dto, $acceptedInvites, $waitingInvites);
    }
}
