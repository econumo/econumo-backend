<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget;

use App\EconumoOneBundle\Application\Budget\Dto\OrderFolderListV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\OrderFolderListV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\OrderFolderListV1ResultAssembler;
use App\EconumoOneBundle\Application\Exception\AccessDeniedException;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetEntityType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetAccessServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureMoveElementDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureOrderItemDto;

readonly class FolderListService
{
    public function __construct(
        private OrderFolderListV1ResultAssembler $orderFolderListV1ResultAssembler,
        private BudgetAccessServiceInterface $budgetAccessService,
        private BudgetServiceInterface $budgetService,
    ) {
    }

    public function orderFolderList(
        OrderFolderListV1RequestDto $dto,
        Id $userId
    ): OrderFolderListV1ResultDto {
        $budgetId = new Id($dto->budgetId);
        if (!$this->budgetAccessService->canUpdateBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }

        $affectedFolders = [];
        foreach ($dto->items as $item) {
            $affectedFolders[$item->id] = new BudgetStructureOrderItemDto(
                new Id($item->id),
                $item->position,
            );
        }
        $this->budgetService->orderFolders($userId, $budgetId, $affectedFolders);
        return $this->orderFolderListV1ResultAssembler->assemble($dto);
    }
}
