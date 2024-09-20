<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\GetStructureV1RequestDto;
use App\Application\Budget\Dto\GetStructureV1ResultDto;
use App\Application\Budget\Assembler\GetStructureV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\BudgetAccessServiceInterface;
use App\Domain\Service\Budget\BudgetServiceInterface;

readonly class StructureService
{
    public function __construct(
        private GetStructureV1ResultAssembler $getStructureV1ResultAssembler,
        private BudgetServiceInterface $budgetService,
        private BudgetAccessServiceInterface $budgetAccessService,
    ) {
    }

    public function getStructure(
        GetStructureV1RequestDto $dto,
        Id $userId
    ): GetStructureV1ResultDto {
        $budgetId = new Id($dto->id);
        if (!$this->budgetAccessService->canReadBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }

        $budgetDto = $this->budgetService->getStructure($userId, $budgetId);
        return $this->getStructureV1ResultAssembler->assemble($userId, $budgetDto);
    }
}
