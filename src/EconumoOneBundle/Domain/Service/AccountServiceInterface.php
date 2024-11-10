<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service;

use App\EconumoOneBundle\Domain\Entity\Account;
use App\EconumoOneBundle\Domain\Entity\Transaction;
use App\EconumoOneBundle\Domain\Entity\ValueObject\AccountName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Dto\AccountDto;
use App\EconumoOneBundle\Domain\Service\Dto\AccountPositionDto;
use DateTimeInterface;

interface AccountServiceInterface
{
    public function create(AccountDto $dto): Account;

    public function delete(Id $id): void;

    public function update(Id $userId, Id $accountId, AccountName $name, Icon $icon = null): void;

    public function updateBalance(Id $accountId, float $balance, \DateTimeInterface $updatedAt, ?string $comment = ''): ?Transaction;

    /**
     * @param Id $userId
     * @param AccountPositionDto[] $changes
     * @return void
     */
    public function orderAccounts(Id $userId, array $changes): void;

    public function getBalance(Id $accountId): float;

    /**
     * @param Id[] $accountsIds
     * @return float[]
     */
    public function getAccountsBalance(array $accountsIds): array;
}
