<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Assembler;

use App\Domain\Entity\Account;
use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\BudgetEntityOptionRepositoryInterface;
use App\Domain\Repository\BudgetEnvelopeRepositoryInterface;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Service\Budget\Dto\BudgetDto;

readonly class BudgetDtoAssembler
{
    public function __construct(
        private BudgetEnvelopeRepositoryInterface $budgetEnvelopeRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private TagRepositoryInterface $tagRepository,
        private AccountRepositoryInterface $accountRepository,
        private BudgetEntityOptionRepositoryInterface $budgetEntityOptionRepository
    ) {
    }

    public function assemble(Id $userId, Budget $budget): BudgetDto
    {
        /** @var Id[] $excludedAccountIds */
        $excludedAccountIds = array_map(function (Account $account) {
            return $account->getId();
        }, $budget->getExcludedAccounts($userId));

        /** @var string[] $allExcludedAccountsFlat */
        $allExcludedAccountsFlat = array_map(function (Account $account) {
            return $account->getId()->getValue();
        }, $budget->getExcludedAccounts());

        $userIds = [];
        $userIds[] = $budget->getUser()->getId();
        $access = $budget->getAccessList();
        foreach ($access as $entry) {
            if ($entry->isAccepted()) {
                $userIds[] = $entry->getUserId();
            }
        }

        $userAccounts = $this->accountRepository->findByOwnersIds($userIds);
        /** @var Id[] $tmpCurrencies */
        $tmpCurrencies = [];
        foreach ($userAccounts as $account) {
            if (!in_array($account->getId()->getValue(), $allExcludedAccountsFlat)) {
                $tmpCurrencies[$account->getCurrencyId()->getValue()] = $account->getCurrencyId();
            }
        }
        $currencies = array_values($tmpCurrencies);

        $categories = [];
        foreach ($this->categoryRepository->findByOwnersIds($userIds) as $category) {
            $categories[$category->getId()->getValue()] = $category->getId();
        }
        $categories = array_values($categories);

        $tags = [];
        foreach ($this->tagRepository->findByOwnersIds($userIds) as $tag) {
            $tags[$tag->getId()->getValue()] = $tag->getId();
        }
        $tags = array_values($tags);

        return new BudgetDto(
            $budget->getId(),
            $budget->getUser()->getId(),
            $budget->getName(),
            $budget->getStartedAt(),
            $excludedAccountIds,
            $currencies,
            $budget->getFolderList(),
            $this->budgetEnvelopeRepository->getByBudgetId($budget->getId()),
            $categories,
            $tags,
            $this->budgetEntityOptionRepository->getByBudgetId($budget->getId()),
            $budget->getAccessList()
        );
    }
}
