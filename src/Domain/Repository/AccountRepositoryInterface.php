<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface AccountRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return Account[]
     */
    public function getAvailableForUserId(Id $userId): array;

    /**
     * @return Account[]
     */
    public function getUserAccounts(Id $userId): array;

    /**
     * @return Account[]
     */
    public function getUserAccountsForBudgeting(Id $userId): array;

    /**
     * @return Account[]
     */
    public function getExcludedUserAccountsForBudgeting(Id $userId): array;

    public function get(Id $id): Account;

    /**
     * @param Account[] $accounts
     */
    public function save(array $accounts): void;

    public function delete(Id $id): void;

    public function getReference(Id $id): Account;

    /**
     * @param Id[] $accountIds
     * @param DateTimeInterface $date
     * @return array
     */
    public function getAccountsBalancesBeforeDate(array $accountIds, DateTimeInterface $date): array;
}
