<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget\Assembler;


use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetEntityAmountRepositoryInterface;
use App\Domain\Service\Budget\Dto\BudgetEntityBudgetAmountDto;
use DateTimeInterface;

readonly class BudgetEntityBudgetAmountDtoAssembler
{
    public function __construct(
        private BudgetEntityAmountRepositoryInterface $budgetEntityAmountRepository,
    ) {
    }

    /**
     * @param Id $budgetId
     * @param DateTimeInterface $periodStart
     * @return BudgetEntityBudgetAmountDto[]
     */
    public function assemble(
        Id $budgetId,
        DateTimeInterface $periodStart
    ): array {
        $result = [];
        $amounts = $this->budgetEntityAmountRepository->getByBudgetId($budgetId, $periodStart);
        foreach ($amounts as $amount) {
            $item = new BudgetEntityBudgetAmountDto(
                $amount->getEntityId(),
                $amount->getEntityType(),
                $amount->getAmount()
            );
            $result[] = $item;
        }

        return $result;
    }
}
