<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget\Assembler;

use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
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
        private AccountRepositoryInterface $accountRepository
    ) {
    }

    public function assemble(Id $userId, Budget $budget): BudgetDto
    {
        /** @var string[] $excludedAccounts */
        $excludedAccounts = array_map(function (Id $accountId) {
            return $accountId->getValue();
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
            if (!in_array($account->getId()->getValue(), $excludedAccounts)) {
                $tmpCurrencies[$account->getCurrencyId()->getValue()] = $account->getCurrencyId();
            }
        }
        $currencies = array_values($tmpCurrencies);

        return new BudgetDto(
            $budget->getId(),
            $budget->getUser()->getId(),
            $budget->getName(),
            $budget->getStartDate(),
            $budget->getExcludedAccounts($userId),
            $currencies,
            $budget->getFolderList(),
            $this->budgetEnvelopeRepository->getByBudgetId($budget->getId()),
            $this->categoryRepository->findByOwnersIds($userIds),
            $this->tagRepository->findByOwnersIds($userIds),
            $budget->getAccessList()
        );
    }
}
