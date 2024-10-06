<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Budget\Assembler;


use App\EconumoBundle\Domain\Entity\Budget;
use App\EconumoBundle\Domain\Entity\ValueObject\BudgetEntityType;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Repository\BudgetEntityAmountRepositoryInterface;
use App\EconumoBundle\Domain\Repository\TransactionRepositoryInterface;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetEntityAmountDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetEntityAmountSpentDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetFiltersDto;

use DateInterval;
use DatePeriod;
use DateTimeInterface;

readonly class BudgetElementsAmountDtoAssembler
{
    public function __construct(
        private BudgetEntityAmountRepositoryInterface $budgetEntityAmountRepository,
        private TransactionRepositoryInterface $transactionRepository,
    ) {
    }

    /**
     * @param Budget $budget
     * @param BudgetFiltersDto $budgetFilters
     * @return BudgetEntityAmountDto[]
     * @throws \DateMalformedPeriodStringException
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

    /**
     * @param Budget $budget
     * @param BudgetFiltersDto $budgetFilters
     * @return array
     * @throws \DateMalformedPeriodStringException
     */
    private function getEntityAmountsForMultipleCurrencies(
        Budget $budget,
        BudgetFiltersDto $budgetFilters,
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
        }

        $result = [];
        foreach ($data as $index => $item) {
            $item = new BudgetEntityAmountDto(
                $item['id'],
                $item['type'],
                $item['tag_id'],
                $item['budget']?->getAmount(),
                round($item['overall_budget'], 2),
                $item['spent'],
                $item['overall_spent']
            );
            $result[$index] = $item;
        }

        return $result;
    }

    /**
     * @param Budget $budget
     * @param BudgetFiltersDto $budgetFilters
     * @return array
     */
    private function getEntityAmountsForOneCurrency(
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
                $item['budget']?->getAmount(),
                round($item['overall_budget'], 2),
                $item['spent'],
                $item['overall_spent']
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
                    'budget' => null,
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
            $data[$index]['budget'] = $amount;
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
                    'budget' => null,
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
            $data[$index]['overall_budget'] += $summarizedAmount['amount'];
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
                    'budget' => null,
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
                round(floatval($item['amount']), 2),
                $periodStart,
                $periodEnd
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
                    'budget' => null,
                    'spent' => [],
                    'spent_sum' => 0,
                    'overall_budget' => 0,
                    'overall_spent' => [],
                    'overall_spent_sum' => 0,
                    'id' => new Id($category['category_id']),
                    'tag_id' => $tagId,
                    'type' => $type,
                    'currency_id' => $category['currency_id'],
                ];
            }
            $data[$index]['spent'][] = new BudgetEntityAmountSpentDto(
                new Id($category['currency_id']),
                round(floatval($category['amount']), 2),
                $periodStart,
                $periodEnd
            );
        }
        return $data;
    }
}
