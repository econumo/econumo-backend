<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Account;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Dto\AccountDto;

interface AccountServiceInterface
{
    public function isAccountAvailable(Id $userId, Id $accountId): bool;

    public function add(AccountDto $dto): Account;

    public function delete(Id $id): void;

    public function update(Id $accountId, string $name, string $icon = null): void;

    public function updateBalance(Id $accountId, float $balance, string $comment = ''): ?Transaction;
}
