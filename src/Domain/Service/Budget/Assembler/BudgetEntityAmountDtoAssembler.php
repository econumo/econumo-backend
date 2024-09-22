<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget\Assembler;


use App\Domain\Entity\ValueObject\BudgetEntityType;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetEntityAmountRepositoryInterface;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\Budget\Dto\BudgetEntityAmountDto;
use App\Domain\Service\Budget\Dto\BudgetEntityAmountSpentDto;
use App\Domain\Service\Budget\Dto\BudgetStructureDto;
use DateTimeInterface;

readonly class BudgetEntityAmountDtoAssembler
{
    public function __construct(
        private BudgetEntityAmountRepositoryInterface $budgetEntityAmountRepository,
        private TransactionRepositoryInterface $transactionRepository,
    ) {
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param BudgetStructureDto $budgetStructure
     * @return BudgetEntityAmountDto[]
     */
    public function assemble(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        BudgetStructureDto $budgetStructure
    ): array {
        $data = [];
        $amounts = $this->budgetEntityAmountRepository->getByBudgetId($budgetStructure->id, $periodStart);
        foreach ($amounts as $amount) {
            $index = $this->getKey($amount->getEntityId()->getValue(), $amount->getEntityType()->getAlias());
            if (!array_key_exists($index, $data)) {
                $data[$index] = [
                    'amount' => null,
                    'spent' => [],
                    'id' => $amount->getEntityId(),
                    'type' => $amount->getEntityType(),
                ];
            }
            $data[$index]['amount'] = $amount;
        }
        $categories = $this->transactionRepository->countSpendingForCategories(
            $budgetStructure->categories,
            $budgetStructure->includedAccountsIds,
            $periodStart,
            $periodEnd
        );
        foreach ($categories as $category) {
            $index = $this->getKey($category['category_id'], BudgetEntityType::category()->getAlias());
            if (!array_key_exists($index, $data)) {
                $data[$index] = [
                    'amount' => null,
                    'spent' => [],
                    'id' => new Id($category['category_id']),
                    'type' => BudgetEntityType::category(),
                ];
            }
            $data[$index]['spent'][] = new BudgetEntityAmountSpentDto(new Id($category['currency_id']), round(floatval($category['amount']), 2));
        }

        $tags = $this->transactionRepository->countSpendingForTags(
            $budgetStructure->tags,
            $budgetStructure->includedAccountsIds,
            $periodStart,
            $periodEnd
        );
        foreach ($tags as $tag) {
            $index = $this->getKey($tag['tag_id'], BudgetEntityType::tag()->getAlias());
            if (!array_key_exists($index, $data)) {
                $data[$index] = [
                    'amount' => null,
                    'spent' => [],
                    'id' => new Id($tag['tag_id']),
                    'type' => BudgetEntityType::tag(),
                ];
            }
            $data[$index]['spent'][] = new BudgetEntityAmountSpentDto(new Id($tag['currency_id']), round(floatval($tag['amount']), 2));
        }

        $result = [];
        foreach ($data as $item) {
            $item = new BudgetEntityAmountDto(
                $item['id'],
                $item['type'],
                $item['amount']?->getAmount(),
                .0, // @TODO available budget
                $item['spent']
            );
            $result[] = $item;
        }

        return $result;
    }

    private function getKey(string $id, string $type): string
    {
        return sprintf('%s_%s', $type, $id);
    }
}
