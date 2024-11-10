<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\Account;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
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

    /**
     * @param Id[] $accountIds
     * @param DateTimeInterface $date
     * @return array
     */
    public function getAccountsBalancesOnDate(array $accountIds, DateTimeInterface $date): array;

    /**
     * @param Id[] $accountIds
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @return array
     */
    public function getAccountsReport(array $accountIds, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array;

    /**
     * @param Id[] $reportAccountIds
     * @param Id[] $holdingAccountIds
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @return array
     */
    public function getHoldingsReport(array $reportAccountIds, array $holdingAccountIds, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array;

    /**
     * @param Id[] $userIds
     * @return Account[]
     */
    public function findByOwnersIds(array $userIds): array;
}
