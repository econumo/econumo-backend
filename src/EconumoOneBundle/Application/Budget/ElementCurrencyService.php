<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget;

use App\EconumoOneBundle\Application\Budget\Dto\ChangeElementCurrencyV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\ChangeElementCurrencyV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\ChangeElementCurrencyV1ResultAssembler;
use App\EconumoOneBundle\Application\Exception\AccessDeniedException;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetAccessServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetElementServiceInterface;

readonly class ElementCurrencyService
{
    public function __construct(
        private ChangeElementCurrencyV1ResultAssembler $changeElementCurrencyV1ResultAssembler,
        private BudgetAccessServiceInterface $budgetAccessService,
        private BudgetElementServiceInterface $budgetElementService,
    ) {
    }

    public function changeElementCurrency(
        ChangeElementCurrencyV1RequestDto $dto,
        Id $userId
    ): ChangeElementCurrencyV1ResultDto {
        $budgetId = new Id($dto->budgetId);
        if (!$this->budgetAccessService->canUpdateBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }

        $elementId = new Id($dto->elementId);
        $currencyId = new Id($dto->currencyId);
        $this->budgetElementService->changeElementCurrency($budgetId, $elementId, $currencyId);
        return $this->changeElementCurrencyV1ResultAssembler->assemble();
    }
}
