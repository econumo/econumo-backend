<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget;

use App\EconumoOneBundle\Application\Budget\Dto\GrantAccessV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\GrantAccessV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\GrantAccessV1ResultAssembler;
use App\EconumoOneBundle\Application\Exception\AccessDeniedException;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetUserRole;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Application\Budget\Dto\AcceptAccessV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\AcceptAccessV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\AcceptAccessV1ResultAssembler;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetAccessServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetSharedAccessServiceInterface;

readonly class AccessService
{
    public function __construct(
        private GrantAccessV1ResultAssembler $grantAccessV1ResultAssembler,
        private AcceptAccessV1ResultAssembler $acceptAccessV1ResultAssembler,
        private BudgetAccessServiceInterface $budgetAccessService,
        private BudgetSharedAccessServiceInterface $budgetSharedAccessService,
        private BudgetServiceInterface $budgetService,
    ) {
    }

    public function grantAccess(
        GrantAccessV1RequestDto $dto,
        Id $userId
    ): GrantAccessV1ResultDto {
        $budgetId = new Id($dto->budgetId);
        if (!$this->budgetAccessService->canShareBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }
        $invitedUserId = new Id($dto->userId);
        $role = BudgetUserRole::createFromAlias($dto->role);

        $this->budgetSharedAccessService->grantAccess($userId, $budgetId, $invitedUserId, $role);
        $budgets = $this->budgetService->getBudgetList($userId);
        return $this->grantAccessV1ResultAssembler->assemble($budgets);
    }

    public function acceptAccess(
        AcceptAccessV1RequestDto $dto,
        Id $userId
    ): AcceptAccessV1ResultDto {
        $budgetId = new Id($dto->budgetId);
        if (!$this->budgetAccessService->canAcceptBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }

        $this->budgetSharedAccessService->acceptAccess($budgetId, $userId);
        $budgets = $this->budgetService->getBudgetList($userId);
        return $this->acceptAccessV1ResultAssembler->assemble($budgets);
    }
}
