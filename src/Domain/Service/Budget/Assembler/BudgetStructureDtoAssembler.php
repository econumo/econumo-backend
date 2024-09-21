<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Assembler;

use App\Domain\Entity\Account;
use App\Domain\Entity\Budget;
use App\Domain\Entity\BudgetEnvelope;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\BudgetEntityOptionRepositoryInterface;
use App\Domain\Repository\BudgetEnvelopeRepositoryInterface;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Service\Budget\Dto\BudgetStructureDto;

readonly class BudgetStructureDtoAssembler
{
    public function __construct(
        private BudgetEnvelopeRepositoryInterface $budgetEnvelopeRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private TagRepositoryInterface $tagRepository,
        private AccountRepositoryInterface $accountRepository,
        private BudgetEntityOptionRepositoryInterface $budgetEntityOptionRepository
    ) {
    }

    /**
     * @param Id $userId
     * @param Budget $budget
     * @return BudgetStructureDto
     */
    public function assemble(
        Id $userId,
        Budget $budget
    ): BudgetStructureDto {
        $excludedAccountIds = $this->getExcludedAccountIds($budget, $userId);
        $allExcludedAccountsFlat = $this->getAllExcludedAccountsFlat($budget);
        $userIds = $this->getBudgetUserIds($budget);
        $includedAccounts = $this->getIncludedUserAccounts($userIds, $allExcludedAccountsFlat);
        $includedAccountsIds = $this->getIncludedUserAccountsIds($includedAccounts);
        $currencies = $this->getCurrencies($includedAccounts);
        $envelopes = $this->getEnvelopes($budget);
        $categories = $this->getCategories($userIds);
        $tags = $this->getTags($userIds);
        $entityOptions = $this->budgetEntityOptionRepository->getByBudgetId($budget->getId());

        return new BudgetStructureDto(
            $budget->getId(),
            $budget->getUser()->getId(),
            $budget->getName(),
            $budget->getStartedAt(),
            $excludedAccountIds,
            $includedAccountsIds,
            $currencies,
            $budget->getFolderList()->toArray(),
            $envelopes,
            $categories,
            $tags,
            $entityOptions,
            $budget->getAccessList()->toArray()
        );
    }

    private function getExcludedAccountIds(Budget $budget, Id $userId): array
    {
        return array_map(fn(Account $account) => $account->getId(), $budget->getExcludedAccounts($userId)->toArray());
    }

    private function getAllExcludedAccountsFlat(Budget $budget): array
    {
        return array_map(fn(Account $account) => $account->getId()->getValue(), $budget->getExcludedAccounts()->toArray());
    }

    private function getBudgetUserIds(Budget $budget): array
    {
        $userIds = [$budget->getUser()->getId()];
        foreach ($budget->getAccessList() as $entry) {
            if ($entry->isAccepted()) {
                $userIds[] = $entry->getUserId();
            }
        }
        return $userIds;
    }

    /**
     * @param Id[] $userIds
     * @param string[] $excludedAccounts
     * @return Account[]
     */
    private function getIncludedUserAccounts(array $userIds, array $excludedAccounts): array
    {
        $userAccounts = $this->accountRepository->findByOwnersIds($userIds);
        $result = [];
        foreach ($userAccounts as $account) {
            if (!in_array($account->getId()->getValue(), $excludedAccounts)) {
                $result[] = $account;
            }
        }
        return $result;
    }

    /**
     * @param Account[] $userAccounts
     * @return Id[]
     */
    private function getIncludedUserAccountsIds(array $userAccounts): array
    {
        return array_map(fn(Account $account) => $account->getId(), $userAccounts);
    }

    /**
     * @param Account[] $userAccounts
     * @return Id[]
     */
    private function getCurrencies(array $userAccounts): array
    {
        $tmpCurrencies = [];
        foreach ($userAccounts as $account) {
            $tmpCurrencies[$account->getCurrencyId()->getValue()] = $account->getCurrencyId();
        }
        return array_values($tmpCurrencies);
    }

    private function getCategories(array $userIds): array
    {
        $categories = [];
        foreach ($this->categoryRepository->findByOwnersIds($userIds) as $category) {
//            if (!$category->isArchived()) {
                $categories[$category->getId()->getValue()] = $category->getId();
//            }
        }
        return array_values($categories);
    }

    private function getTags(array $userIds): array
    {
        $tags = [];
        foreach ($this->tagRepository->findByOwnersIds($userIds) as $tag) {
//            if (!$tag->isArchived()) {
                $tags[$tag->getId()->getValue()] = $tag->getId();
//            }
        }
        return array_values($tags);
    }

    /**
     * @param Budget $budget
     * @return BudgetEnvelope[]
     */
    private function getEnvelopes(Budget $budget): array
    {
        $envelopes = [];
        foreach($this->budgetEnvelopeRepository->getByBudgetId($budget->getId()) as $envelope) {
//            if (!$envelope->isArchived()) {
                $envelopes[$envelope->getId()->getValue()] = $envelope->getId();
//            }
        }
        return array_values($envelopes);
    }
}