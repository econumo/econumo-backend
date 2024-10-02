<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget\Assembler;


use App\Domain\Entity\Budget;
use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\BudgetEntityType;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetEntityAmountRepositoryInterface;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\Budget\Dto\BudgetEntityAmountDto;
use App\Domain\Service\Budget\Dto\BudgetEntityAmountSpentDto;
use App\Domain\Service\Budget\Dto\BudgetFiltersDto;
use App\Domain\Service\Currency\CurrencyConvertorInterface;
use App\Domain\Service\Currency\Dto\CurrencyConvertorDto;
use DateInterval;
use DatePeriod;
use DateTimeInterface;

readonly class BudgetElementsAmountDtoAssembler
{
    public function __construct(
        private BudgetEntityAmountRepositoryInterface $budgetEntityAmountRepository,
        private TransactionRepositoryInterface $transactionRepository,
        private CurrencyConvertorInterface $currencyConvertor
    ) {
    }

    /**
     * @param Budget $budget
     * @param BudgetFiltersDto $budgetFilters
     * @return BudgetEntityAmountDto[]
     */
    public function assemble(
        Budget $budget,
        BudgetFiltersDto $budgetFilters
    ): array {
        if (count($budgetFilters->currenciesIds) === 1) {
            return $this->getEntityAmountsForOneCurrency($budget, $budgetFilters);
        }
        return $this->getEntityAmountsForMultipleCurrencies($budget, $budgetFilters);
    }

    private function getEntityAmountsForMultipleCurrencies(
        Budget $budget,
        BudgetFiltersDto $budgetFilters
    ): array {
        $data = [];
        $data = $this->generateByAmounts($budget, $budgetFilters->periodStart, $data);
        $data = $this->countSpending(
            $budgetFilters,
            $budgetFilters->periodStart,
            $budgetFilters->periodEnd,
            $data
        );
        $data = $this->countSummarizedAmounts(
            $budget,
            $budget->getStartedAt(),
            $budgetFilters->periodStart,
            $data
        );

        $interval = new DateInterval('P1M');
        $period = new DatePeriod($budget->getStartedAt(), $interval, $budgetFilters->periodStart);
        foreach ($period as $startDate) {
            $endDate = clone $startDate;
            $endDate->modify('next month');
            $data = $this->countSummarizedSpending($budget, $budgetFilters, $startDate, $endDate, $data);

            $toConvert = [];
            foreach ($data as $key => $item) {
                /** @var BudgetEntityAmountSpentDto $amount */
                foreach ($item['overall_spent'] as $amount) {
                    if (!array_key_exists($key, $toConvert)) {
                        $toConvert[$key] = [];
                    }
                    $toConvert[$key][] = new CurrencyConvertorDto(
                        $amount->currencyId,
                        $budget->getCurrencyId(),
                        $amount->amount
                    );
                }
            }
            $amounts = $this->currencyConvertor->bulkConvert($startDate, $endDate, $toConvert);
            foreach ($amounts as $key => $amount) {
                $data[$key]['overall_spent_sum'] += $amount;
            }
        }
        $toConvert = [];
        foreach ($data as $key => $item) {
            /** @var BudgetEntityAmountSpentDto $amount */
            foreach ($item['spent'] as $amount) {
                if (!array_key_exists($key, $toConvert)) {
                    $toConvert[$key] = [];
                }
                $toConvert[$key][] = new CurrencyConvertorDto(
                    $amount->currencyId,
                    $budget->getCurrencyId(),
                    $amount->amount
                );
            }
        }
        $amounts = $this->currencyConvertor->bulkConvert($budgetFilters->periodStart, $budgetFilters->periodEnd, $toConvert);
        foreach ($amounts as $key => $amount) {
            $data[$key]['spent_sum'] += $amount;
        }

        $result = [];
        foreach ($data as $item) {
            $index = $this->getKey($item['id']->getValue(), $item['type']->getAlias());
            $item = new BudgetEntityAmountDto(
                $item['id'],
                $item['type'],
                $item['tag_id'],
                $item['amount']?->getAmount(),
                round($item['overall_budget'] - $item['overall_spent_sum'], 2),
                round($item['spent_sum'], 2),
                $item['spent']
            );
            $result[$index] = $item;
        }

        return $result;
    }

    private function getEntityAmountsForOneCurrency(
        Budget $budget,
        BudgetFiltersDto $budgetFilters
    ): array {
        $data = [];
        $data = $this->generateByAmounts($budget, $budgetFilters, $budgetFilters->periodStart, $data);
        $data = $this->countSpending(
            $budgetFilters,
            $budgetFilters->periodStart,
            $budgetFilters->periodEnd,
            $data
        );
        $data = $this->countSummarizedAmounts(
            $budget,
            $budgetFilters,
            $budget->getStartedAt(),
            $budgetFilters->periodStart,
            $data
        );
        $data = $this->countSummarizedSpending(
            $budget,
            $budgetFilters,
            $budget->getStartedAt(),
            $budgetFilters->periodEnd,
            $data
        );

        $result = [];
        foreach ($data as $item) {
            $item = new BudgetEntityAmountDto(
                $item['id'],
                $item['type'],
                $item['tag_id'],
                $item['amount']?->getAmount(),
                round($item['overall_budget'] - array_sum($item['overall_spent']), 2),
                $item['spent']
            );
            $result[] = $item;
        }

        return $result;
    }

    private function getKey(string $id, string $type): string
    {
        return sprintf('%s-%s', $id, $type);
    }

    /**
     * @param Budget $budget
     * @param DateTimeInterface $periodStart
     * @param array $data
     * @return array
     */
    private function generateByAmounts(
        Budget $budget,
        DateTimeInterface $periodStart,
        array $data
    ): array {
        $amounts = $this->budgetEntityAmountRepository->getByBudgetId($budget->getId(), $periodStart);
        foreach ($amounts as $amount) {
            $index = $this->getKey($amount->getEntityId()->getValue(), $amount->getEntityType()->getAlias());
            if (!array_key_exists($index, $data)) {
                $data[$index] = [
                    'amount' => null,
                    'spent' => [],
                    'spent_sum' => 0,
                    'overall_budget' => 0,
                    'overall_spent' => [],
                    'overall_spent_sum' => 0,
                    'id' => $amount->getEntityId(),
                    'tag_id' => null,
                    'type' => $amount->getEntityType(),
                ];
            }
            $data[$index]['amount'] = $amount;
        }
        return $data;
    }

    /**
     * @param Budget $budget
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param array $data
     * @return array
     */
    private function countSummarizedAmounts(
        Budget $budget,
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        array $data
    ): array {
        $summarizedAmounts = $this->budgetEntityAmountRepository->getSummarizedAmounts(
            $budget->getId(),
            $periodStart,
            $periodEnd
        );
        foreach ($summarizedAmounts as $summarizedAmount) {
            $index = $this->getKey(
                $summarizedAmount['entityId']->getValue(),
                $summarizedAmount['entityType']->getAlias()
            );
            if (!array_key_exists($index, $data)) {
                $data[$index] = [
                    'amount' => null,
                    'spent' => [],
                    'spent_sum' => 0,
                    'overall_budget' => 0,
                    'overall_spent' => [],
                    'overall_spent_sum' => 0,
                    'id' => $summarizedAmount['entityId'],
                    'tag_id' => null,
                    'type' => $summarizedAmount['entityType'],
                ];
            }
            $data[$index]['overall_budget'] += $summarizedAmount['budget'];
        }
        return $data;
    }

    /**
     * @param Budget $budget
     * @param BudgetFiltersDto $budgetFilters
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param array $data
     * @return array
     */
    private function countSummarizedSpending(
        Budget $budget,
        BudgetFiltersDto $budgetFilters,
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        array $data
    ): array {
        $categoriesIds = [];
        foreach ($budgetFilters->categories as $category) {
            if ($category->getType()->isIncome()) {
                continue;
            }
            $categoriesIds[] = $category->getId();
        }
        $spending = $this->transactionRepository->countSpending(
            $categoriesIds,
            $budgetFilters->includedAccountsIds,
            $periodStart,
            $periodEnd
        );
        foreach ($spending as $item) {
            if (empty($item['tag_id'])) {
                $type = BudgetEntityType::category();
            } else {
                $type = BudgetEntityType::tag();
            }
            $index = $this->getKey($item['category_id'], $type->getAlias());
            if (!array_key_exists($index, $data)) {
                $data[$index] = [
                    'amount' => null,
                    'spent' => [],
                    'spent_sum' => 0,
                    'overall_budget' => 0,
                    'overall_spent' => [],
                    'overall_spent_sum' => 0,
                    'id' => new Id($item['category_id']),
                    'tag_id' => (empty($item['tag_id']) ? null : new Id($item['tag_id'])),
                    'type' => BudgetEntityType::tag(),
                ];
            }
            $data[$index]['overall_spent'][] = new BudgetEntityAmountSpentDto(
                new Id($item['currency_id']),
                round(floatval($item['amount']), 2)
            );
        }
        return $data;
    }

    /**
     * @param BudgetFiltersDto $budgetFilters
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param array $data
     * @return array
     */
    private function countSpending(
        BudgetFiltersDto $budgetFilters,
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        array $data
    ): array {
        $categoriesIds = [];
        foreach ($budgetFilters->categories as $category) {
            if ($category->getType()->isIncome()) {
                continue;
            }
            $categoriesIds[] = $category->getId();
        }
        $spending = $this->transactionRepository->countSpending(
            $categoriesIds,
            $budgetFilters->includedAccountsIds,
            $periodStart,
            $periodEnd
        );
        foreach ($spending as $category) {
            if (empty($category['tag_id'])) {
                $type = BudgetEntityType::category();
                $tagId = null;
            } else {
                $type = BudgetEntityType::tag();
                $tagId = new Id($category['tag_id']);
            }
            $index = $this->getKey($category['category_id'], $type->getAlias());
            if (!array_key_exists($index, $data)) {
                $data[$index] = [
                    'amount' => null,
                    'spent' => [],
                    'spent_sum' => 0,
                    'overall_budget' => 0,
                    'overall_spent' => [],
                    'overall_spent_sum' => 0,
                    'id' => new Id($category['category_id']),
                    'tag_id' => $tagId,
                    'type' => $type,
                ];
            }
            $data[$index]['spent'][] = new BudgetEntityAmountSpentDto(
                new Id($category['currency_id']),
                round(floatval($category['amount']), 2)
            );
        }
        return $data;
    }
}
