<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\Category;
use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\DomainException;
use App\Domain\Exception\NotFoundException;
use App\Domain\Factory\EnvelopeBudgetFactoryInterface;
use App\Domain\Factory\EnvelopeFactoryInterface;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Repository\EnvelopeBudgetRepositoryInterface;
use App\Domain\Repository\EnvelopeRepositoryInterface;
use App\Domain\Repository\PlanFolderRepositoryInterface;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\AntiCorruptionServiceInterface;
use DateTime;
use DateTimeInterface;
use Throwable;

readonly class EnvelopeService implements EnvelopeServiceInterface
{
    public function __construct(
        private EnvelopeFactoryInterface $envelopeFactory,
        private PlanRepositoryInterface $planRepository,
        private UserRepositoryInterface $userRepository,
        private CurrencyRepositoryInterface $currencyRepository,
        private EnvelopeRepositoryInterface $envelopeRepository,
        private PlanFolderRepositoryInterface $planFolderRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private TagRepositoryInterface $tagRepository,
        private EnvelopeBudgetRepositoryInterface $envelopeBudgetRepository,
        private PlanReportService $planReportService,
        private EnvelopeBudgetFactoryInterface $envelopeBudgetFactory,
        private AntiCorruptionServiceInterface $antiCorruptionService,
    ) {
    }

    public function createConnectedEnvelopesByCategory(Category $category, Id $userId): void
    {
        $plans = $this->planRepository->getAvailableForUserId($userId);
        $user = $this->userRepository->get($userId);
        $userCurrency = $this->currencyRepository->getByCode($user->getCurrency());
        $envelopes = [];
        $isExpense = $category->getType()->isExpense();
        foreach ($plans as $plan) {
            $planEnvelopes = $this->envelopeRepository->getByPlanId($plan->getId());
            $envelopePosition = 0;
            foreach ($planEnvelopes as $planEnvelope) {
                if ($planEnvelope->getType()->isIncome()) {
                    continue;
                }
                if ($planEnvelope->getPosition() > $envelopePosition) {
                    $envelopePosition = $planEnvelope->getPosition();
                }
            }
            $envelopePosition = $envelopePosition === 0 ? 0 : $envelopePosition + 1;

            $folderId = null;
            if ($isExpense) {
                $planFolders = $this->planFolderRepository->getByPlanId($plan->getId());
                if (count($planFolders) > 0) {
                    $folderId = $planFolders[count($planFolders) - 1]->getId();
                }
            }
            $envelopes[] = $this->envelopeFactory->createFromCategory(
                $plan->getId(),
                $category,
                $userCurrency->getId(),
                $envelopePosition,
                $folderId
            );
        }
        $this->planRepository->save($envelopes);
    }

    public function createConnectedEnvelopesByTag(Tag $tag, Id $userId): void
    {
        $plans = $this->planRepository->getAvailableForUserId($userId);
        $user = $this->userRepository->get($userId);
        $userCurrency = $this->currencyRepository->getByCode($user->getCurrency());
        $envelopes = [];
        foreach ($plans as $plan) {
            $planEnvelopes = $this->envelopeRepository->getByPlanId($plan->getId());
            $envelopePosition = 0;
            if (count($planEnvelopes) > 0) {
                $envelopePosition = $planEnvelopes[count($planEnvelopes) - 1]->getPosition() + 1;
            }

            $folderId = null;
            $planFolders = $this->planFolderRepository->getByPlanId($plan->getId());
            if (count($planFolders) > 0) {
                $folderId = $planFolders[count($planFolders) - 1]->getId();
            }
            $envelopes[] = $this->envelopeFactory->createFromTag(
                $plan->getId(),
                $tag,
                $userCurrency->getId(),
                $envelopePosition,
                $folderId
            );
        }
        $this->planRepository->save($envelopes);
    }

    public function createEnvelopesForUser(
        Id $planId,
        Id $userId,
        Id $currencyId,
        int &$envelopePosition,
        Id $folderId
    ): void {
        $envelopes = [];
        $categories = $this->categoryRepository->findByOwnerId($userId);

        foreach ($categories as $category) {
            if ($category->getType()->isIncome()) {
                $envelopes[] = $this->envelopeFactory->createFromCategory(
                    $planId,
                    $category,
                    $currencyId,
                    $envelopePosition++,
                    null
                );
            }
        }
        foreach ($categories as $category) {
            if ($category->getType()->isExpense()) {
                $envelopes[] = $this->envelopeFactory->createFromCategory(
                    $planId,
                    $category,
                    $currencyId,
                    $envelopePosition++,
                    $folderId
                );
            }
        }
        $tags = $this->tagRepository->findByOwnerId($userId);
        foreach ($tags as $tag) {
            $envelopes[] = $this->envelopeFactory->createFromTag(
                $planId,
                $tag,
                $currencyId,
                $envelopePosition++,
                $folderId
            );
        }
        $this->envelopeRepository->save($envelopes);
    }

    public function getEnvelopesBudgets(Id $planId, DateTimeInterface $date): array
    {
        $items = $this->envelopeBudgetRepository->getByPlanIdAndPeriod($planId, $date);
        $result = [];
        foreach ($items as $item) {
            $result[$item->getEnvelope()->getId()->getValue()] = $item;
        }

        return $result;
    }

    public function getEnvelopesAvailable(Id $planId, DateTimeInterface $date): array
    {
        $plan = $this->planRepository->get($planId);
        $planStartDate = $plan->getStartDate();
        $envelopes = $this->envelopeRepository->getByPlanId($plan->getId());
        $result = [];
        if ($planStartDate > $date) {
            return $result;
        }

        $categoriesReport = $this->planReportService->getCategoriesReport($planId, $planStartDate, $date);
        $tagsReport = $this->planReportService->getTagsReport($planId, $planStartDate, $date);
        $budget = $this->envelopeBudgetRepository->getSumByPlanIdAndPeriod($planId, $date);

        foreach ($envelopes as $envelope) {
            $result[$envelope->getId()->getValue()] = null;
            foreach ($envelope->getCategories() as $category) {
                if (isset($categoriesReport[$category->getId()->getValue()])) {
                    $result[$envelope->getId()->getValue()] += $categoriesReport[$category->getId()->getValue()];
                }
            }
            foreach ($envelope->getTags() as $tag) {
                if (isset($tagsReport[$tag->getId()->getValue()])) {
                    $result[$envelope->getId()->getValue()] += $tagsReport[$tag->getId()->getValue()];
                }
            }
        }
        foreach ($result as $envelopeId => $amount) {
            if (array_key_exists($envelopeId, $budget)) {
                $result[$envelopeId] = $budget[$envelopeId]['budget'] - $amount;
            }
        }

        return $result;
    }

    public function updateEnvelopeBudget(Id $envelopeId, DateTimeInterface $period, float $amount): void
    {
        $envelope = $this->envelopeRepository->get($envelopeId);
        $plan = $envelope->getPlan();
        $updatedPeriod = DateTime::createFromFormat('Y-m-d H:i:s', $period->format('Y-m-01 00:00:00'));
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            if ($plan->getStartDate() > $updatedPeriod) {
                $plan->updateStartDate($updatedPeriod);
                $this->planRepository->save([$plan]);
            }
            try {
                $envelopeBudget = $this->envelopeBudgetRepository->getByEnvelopeIdAndPeriod(
                    $envelopeId,
                    $updatedPeriod
                );
            } catch (NotFoundException) {
                $envelopeBudget = $this->envelopeBudgetFactory->create($envelopeId, $updatedPeriod, $amount);
            }
            $envelopeBudget->updateAmount($amount);
            $this->envelopeBudgetRepository->save([$envelopeBudget]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }
    }

    public function transferEnvelopeBudget(
        Id $fromEnvelopeId,
        Id $toEnvelopeId,
        DateTimeInterface $period,
        float $amount
    ): void {
        $fromEnvelope = $this->envelopeRepository->get($fromEnvelopeId);
        $toEnvelope = $this->envelopeRepository->get($toEnvelopeId);
        $plan = $fromEnvelope->getPlan();
        if (!$fromEnvelope->getPlan()->getId()->isEqual($toEnvelope->getPlan()->getId())) {
            throw new DomainException();
        }

        $updatedPeriod = DateTime::createFromFormat('Y-m-d H:i:s', $period->format('Y-m-01 00:00:00'));
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            if ($plan->getStartDate() > $updatedPeriod) {
                $plan->updateStartDate($updatedPeriod);
                $this->planRepository->save([$plan]);
            }
            try {
                $fromEnvelopeBudget = $this->envelopeBudgetRepository->getByEnvelopeIdAndPeriod(
                    $fromEnvelopeId,
                    $updatedPeriod
                );
            } catch (NotFoundException) {
                $fromEnvelopeBudget = $this->envelopeBudgetFactory->create($fromEnvelopeId, $updatedPeriod, 0);
            }
            try {
                $toEnvelopeBudget = $this->envelopeBudgetRepository->getByEnvelopeIdAndPeriod(
                    $toEnvelopeId,
                    $updatedPeriod
                );
            } catch (NotFoundException) {
                $toEnvelopeBudget = $this->envelopeBudgetFactory->create($toEnvelopeId, $updatedPeriod, 0);
            }

            $fromEnvelopeBudget->updateAmount($fromEnvelopeBudget->getAmount() - $amount);
            $toEnvelopeBudget->updateAmount($toEnvelopeBudget->getAmount() + $amount);
            $this->envelopeBudgetRepository->save([$fromEnvelopeBudget, $toEnvelopeBudget]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }
    }
}
