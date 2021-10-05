<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Dto\AccountDto;

interface AccountServiceInterface
{
    public function isAccountAvailable(Id $userId, Id $accountId): bool;

    public function add(AccountDto $dto): Account;
}
