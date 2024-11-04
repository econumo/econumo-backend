<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Connection;

use App\EconumoOneBundle\Application\Connection\Assembler\SetAccountAccessV1ResultAssembler;
use App\EconumoOneBundle\Application\Connection\Dto\RevokeAccountAccessV1RequestDto;
use App\EconumoOneBundle\Application\Connection\Dto\RevokeAccountAccessV1ResultDto;
use App\EconumoOneBundle\Application\Connection\Assembler\RevokeAccountAccessV1ResultAssembler;
use App\EconumoOneBundle\Application\Connection\Dto\SetAccountAccessV1RequestDto;
use App\EconumoOneBundle\Application\Connection\Dto\SetAccountAccessV1ResultDto;
use App\EconumoOneBundle\Application\Exception\AccessDeniedException;
use App\EconumoOneBundle\Domain\Entity\ValueObject\AccountUserRole;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\AccountAccessServiceInterface;
use App\EconumoOneBundle\Domain\Service\Connection\ConnectionAccountServiceInterface;

class AccountAccessService
{
    public function __construct(private readonly SetAccountAccessV1ResultAssembler $setAccountAccessV1ResultAssembler, private readonly ConnectionAccountServiceInterface $connectionAccountService, private readonly AccountAccessServiceInterface $accountAccessService, private readonly RevokeAccountAccessV1ResultAssembler $revokeAccountAccessV1ResultAssembler)
    {
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

    public function revokeAccountAccess(
        RevokeAccountAccessV1RequestDto $dto,
        Id $userId
    ): RevokeAccountAccessV1ResultDto {
        $accountId = new Id($dto->accountId);
        if (!$this->accountAccessService->canUpdateAccount($userId, $accountId)) {
            throw new AccessDeniedException();
        }

        $affectedUserId = new Id($dto->userId);
        $this->connectionAccountService->revokeAccountAccess($affectedUserId, $accountId);
        return $this->revokeAccountAccessV1ResultAssembler->assemble($dto);
    }
}
