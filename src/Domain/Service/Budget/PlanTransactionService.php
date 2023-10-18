<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\EnvelopeRepositoryInterface;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\Currency\CurrencyConvertorInterface;
use DateTimeInterface;

readonly class PlanTransactionService
{
    public function __construct(
        private TransactionRepositoryInterface $transactionRepository,
        private EnvelopeRepositoryInterface $envelopeRepository,
        private CurrencyConvertorInterface $currencyConvertor,
    ) {
    }

    public function getCategoriesReport(
        Id $planId,
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd
    ): array {
        $envelopes = $this->envelopeRepository->getByPlanId($planId);

        $categoriesIds = [];
        $categoriesMapping = [];
        foreach ($envelopes as $envelope) {
            foreach ($envelope->getCategories() as $category) {
                $categoriesIds[$category->getId()->getValue()] = $category->getId();
                $categoriesMapping[$category->getId()->getValue()] = $envelope->getCurrency()->getId();
            }
        }
        $categoriesSpending = $this->transactionRepository->countSpendingForCategories(
            $categoriesIds,
            $periodStart,
            $periodEnd
        );
        $result = [];
        foreach ($categoriesSpending as $item) {
            if (!isset($result[$item['category_id']])) {
                $result[$item['category_id']] = 0;
            }
            $itemCurrencyId = new Id($item['currency_id']);
            if ($categoriesMapping[$item['category_id']]->isEqual($itemCurrencyId)) {
                $result[$item['category_id']] += $item['amount'];
            } else {
                $result[$item['category_id']] += $this->currencyConvertor->convertForPeriod(
                    $itemCurrencyId,
                    $categoriesMapping[$item['category_id']],
                    (float)$item['amount'],
                    $periodStart,
                    $periodEnd
                );
            }
        }
        return $result;
    }

    public function getTagsReport(
        Id $planId,
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd
    ): array {
        $envelopes = $this->envelopeRepository->getByPlanId($planId);
        $tagsIds = [];
        $tagsMapping = [];
        foreach ($envelopes as $envelope) {
            foreach ($envelope->getTags() as $tag) {
                $tagsIds[$tag->getId()->getValue()] = $tag->getId();
                $tagsMapping[$tag->getId()->getValue()] = $envelope->getCurrency()->getId();
            }
        }
        $tagsSpending = $this->transactionRepository->countSpendingForTags(
            $tagsIds,
            $periodStart,
            $periodEnd
        );
        $result = [];
        foreach ($tagsSpending as $item) {
            if (!isset($result[$item['tag_id']])) {
                $result[$item['tag_id']] = 0;
            }
            $itemCurrencyId = new Id($item['currency_id']);
            if ($tagsMapping[$item['tag_id']]->isEqual($itemCurrencyId)) {
                $result[$item['tag_id']] += $item['amount'];
            } else {
                $result[$item['tag_id']] += $this->currencyConvertor->convertForPeriod(
                    $itemCurrencyId,
                    $tagsMapping[$item['tag_id']],
                    (float)$item['amount'],
                    $periodStart,
                    $periodEnd
                );
            }
        }
        return $result;
    }
}
