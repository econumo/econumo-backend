<?php

declare(strict_types=1);

namespace App\Application\Connection;

use App\Application\Connection\Dto\SetAccountAccessV1RequestDto;
use App\Application\Connection\Dto\SetAccountAccessV1ResultDto;
use App\Application\Connection\Assembler\SetAccountAccessV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Domain\Entity\ValueObject\AccountUserRole;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\Connection\ConnectionAccountServiceInterface;

class AccountAccessService
{
    private SetAccountAccessV1ResultAssembler $setAccountAccessV1ResultAssembler;
    private ConnectionAccountServiceInterface $connectionAccountService;
    private AccountAccessServiceInterface $accountAccessService;

    public function __construct(
        SetAccountAccessV1ResultAssembler $setAccountAccessV1ResultAssembler,
        ConnectionAccountServiceInterface $connectionAccountService,
        AccountAccessServiceInterface $accountAccessService
    ) {
        $this->setAccountAccessV1ResultAssembler = $setAccountAccessV1ResultAssembler;
        $this->connectionAccountService = $connectionAccountService;
        $this->accountAccessService = $accountAccessService;
    }

    public function setAccountAccess(
        SetAccountAccessV1RequestDto $dto,
        Id $userId
    ): SetAccountAccessV1ResultDto {
        $accountId = new Id($dto->accountId);
        if (!$this->accountAccessService->canUpdateAccount($userId, $accountId)) {
            throw new AccessDeniedException();
        }

        $affectedUserId = new Id($dto->userId);
        $role = AccountUserRole::createFromAlias($dto->role);
        $this->connectionAccountService->setAccountAccess($affectedUserId, $accountId, $role);
        return $this->setAccountAccessV1ResultAssembler->assemble($dto);
    }
}
