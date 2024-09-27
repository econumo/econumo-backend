<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Assembler;

use App\Domain\Entity\Account;
use App\Domain\Entity\Budget;
use App\Domain\Entity\Category;
use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Service\Budget\Dto\BudgetFiltersDto;
use ArrayObject;
use DateTimeInterface;
use SplFixedArray;

readonly class BudgetFiltersDtoAssembler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private TagRepositoryInterface $tagRepository,
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    /**
     * @param Budget $budget
     * @param Id $userId
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @return BudgetFiltersDto
     */
    public function assemble(
        Budget $budget,
        Id $userId,
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd
    ): BudgetFiltersDto {
        $excludedAccountIds = $this->getExcludedAccountIds($budget, $userId);
        $userIds = $this->getBudgetUserIds($budget);
        $includedAccounts = $this->getIncludedUserAccounts($userIds, $excludedAccountIds);
        $includedAccountsIds = $this->getIncludedUserAccountsIds($includedAccounts);
        $currenciesIds = $this->getCurrenciesIds($includedAccounts);
        $categories = $this->getCategories($userIds);
        $tags = $this->getTags($userIds);

        return new BudgetFiltersDto(
            $periodStart,
            $periodEnd,
            $userIds,
            $excludedAccountIds,
            $includedAccountsIds,
            $currenciesIds,
            $categories,
            $tags
        );
    }

    private function getExcludedAccountIds(Budget $budget, Id $userId): array
    {
        return array_map(fn(Account $account) => $account->getId(), $budget->getExcludedAccounts($userId)->toArray());
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
     * @param Id[] $excludedAccountsIds
     * @return Account[]
     */
    private function getIncludedUserAccounts(array $userIds, array $excludedAccountsIds): array
    {
        $excludedAccounts = array_map(fn(Id $accountId) => $accountId->getValue(), $excludedAccountsIds);
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
    private function getCurrenciesIds(array $userAccounts): array
    {
        $tmpCurrencies = [];
        foreach ($userAccounts as $account) {
            $tmpCurrencies[$account->getCurrencyId()->getValue()] = $account->getCurrencyId();
        }
        return array_values($tmpCurrencies);
    }

    /**
     * @param array $userIds
     * @return ArrayObject<Category>
     */
    private function getCategories(array $userIds): ArrayObject
    {
        $result = new ArrayObject();
        foreach ($this->categoryRepository->findByOwnersIds($userIds) as $category) {
            $result[$category->getId()->getValue()] = $category;
        }
        return $result;
    }

    /**
     * @param array $userIds
     * @return ArrayObject<Tag>
     */
    private function getTags(array $userIds): ArrayObject
    {
        $result = new ArrayObject();
        foreach ($this->tagRepository->findByOwnersIds($userIds) as $tag) {
            $result[$tag->getId()->getValue()] = $tag;
        }
        return $result;
    }
}