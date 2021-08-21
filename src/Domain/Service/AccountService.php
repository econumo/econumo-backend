<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;

class AccountService implements AccountServiceInterface
{
    private AccountRepositoryInterface $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function isAccountAvailable(Id $userId, Id $accountId): bool
    {
        $accounts = $this->accountRepository->findByUserId($userId);
        foreach ($accounts as $account) {
            if ($account->getId()->isEqual($accountId)) {
                return true;
            }
        }

        return false;
    }
}
