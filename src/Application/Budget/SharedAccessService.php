<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\RevokeSharedAccessV1RequestDto;
use App\Application\Budget\Dto\RevokeSharedAccessV1ResultDto;
use App\Application\Budget\Assembler\RevokeSharedAccessV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\UserRole;
use App\Domain\Exception\RevokeOwnerAccessException;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Domain\Service\Budget\PlanAccessServiceInterface;
use App\Domain\Service\Budget\PlanServiceInterface;
use App\Application\Budget\Dto\GrantSharedAccessV1RequestDto;
use App\Application\Budget\Dto\GrantSharedAccessV1ResultDto;
use App\Application\Budget\Assembler\GrantSharedAccessV1ResultAssembler;

readonly class SharedAccessService
{
    public function __construct(
        private RevokeSharedAccessV1ResultAssembler $revokeSharedAccessV1ResultAssembler,
        private PlanAccessServiceInterface $planAccessService,
        private PlanServiceInterface $planService,
        private PlanRepositoryInterface $planRepository,
        private GrantSharedAccessV1ResultAssembler $grantSharedAccessV1ResultAssembler
    ) {
    }

    public function revokeSharedAccess(
        RevokeSharedAccessV1RequestDto $dto,
        Id $userId
    ): RevokeSharedAccessV1ResultDto {
        $planId = new Id($dto->planId);
        if (!$this->planAccessService->canManagePlanAccess($userId, $planId)) {
            throw new AccessDeniedException();
        }
        $sharedUserId = new Id($dto->userId);
        try {
            $this->planService->revokeSharedAccess($planId, $sharedUserId);
            $plan = $this->planRepository->get($planId);
            return $this->revokeSharedAccessV1ResultAssembler->assemble($dto, $plan, $userId);
        } catch (RevokeOwnerAccessException $e) {
            throw new ValidationException();
        }
    }

    public function grantSharedAccess(
        GrantSharedAccessV1RequestDto $dto,
        Id $userId
    ): GrantSharedAccessV1ResultDto {
        $planId = new Id($dto->planId);
        if (!$this->planAccessService->canManagePlanAccess($userId, $planId)) {
            throw new AccessDeniedException();
        }
        $sharedUserId = new Id($dto->userId);
        $role = UserRole::createFromAlias($dto->role);
        $this->planService->grantSharedAccess($planId, $sharedUserId, $role);
        $plan = $this->planRepository->get($planId);
        return $this->grantSharedAccessV1ResultAssembler->assemble($dto, $plan, $userId);
    }
}
