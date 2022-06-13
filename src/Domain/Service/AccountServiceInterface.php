<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Account;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\AccountName;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Dto\AccountDto;
use App\Domain\Service\Dto\AccountPositionDto;
use DateTimeInterface;

interface AccountServiceInterface
{
    public function create(AccountDto $dto): Account;

    public function delete(Id $id): void;

    public function update(Id $userId, Id $accountId, AccountName $name, Icon $icon = null): void;

    public function updateBalance(Id $accountId, float $balance, \DateTimeInterface $updatedAt, ?string $comment = ''): ?Transaction;

    public function orderAccounts(Id $userId, AccountPositionDto ...$changes): void;
}
