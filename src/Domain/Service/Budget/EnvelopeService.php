<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\Category;
use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\EnvelopeFactoryInterface;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Repository\EnvelopeRepositoryInterface;
use App\Domain\Repository\PlanFolderRepositoryInterface;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

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
}
