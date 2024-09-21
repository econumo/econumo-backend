<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget\Assembler;


use App\Domain\Entity\ValueObject\BudgetEntityType;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\Budget\Dto\BudgetEntitySpendAmountDto;
use App\Domain\Service\Budget\Dto\BudgetStructureDto;
use DateTimeInterface;

readonly class BudgetEntitySpendAmountDtoAssembler
{
    public function __construct(
        private TransactionRepositoryInterface $transactionRepository,
    ) {
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param BudgetStructureDto $budgetStructureDto
     * @return BudgetEntitySpendAmountDto[]
     */
    public function assemble(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        BudgetStructureDto $budgetStructureDto
    ): array {
        $result = [];
        $categories = $this->transactionRepository->countSpendingForCategories(
            $budgetStructureDto->categories,
            $budgetStructureDto->includedAccountsIds,
            $periodStart,
            $periodEnd
        );
        foreach ($categories as $category) {
            $item = new BudgetEntitySpendAmountDto(
                new Id($category['category_id']),
                BudgetEntityType::category(),
                new Id($category['currency_id']),
                floatval($category['amount'])
            );
            $result[] = $item;
        }

        $tags = $this->transactionRepository->countSpendingForTags(
            $budgetStructureDto->tags,
            $budgetStructureDto->includedAccountsIds,
            $periodStart,
            $periodEnd
        );
        foreach ($tags as $tag) {
            $item = new BudgetEntitySpendAmountDto(
                new Id($tag['tag_id']),
                BudgetEntityType::tag(),
                new Id($tag['currency_id']),
                floatval($tag['amount'])
            );
            $result[] = $item;
        }

        return $result;
    }
}
