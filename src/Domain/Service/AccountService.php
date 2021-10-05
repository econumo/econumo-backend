<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\AccountType;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\AccountFactoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Service\Dto\AccountDto;

class AccountService implements AccountServiceInterface
{
    private AccountRepositoryInterface $accountRepository;
    private AccountFactoryInterface $accountFactory;

    public function __construct(AccountRepositoryInterface $accountRepository, AccountFactoryInterface $accountFactory)
    {
        $this->accountRepository = $accountRepository;
        $this->accountFactory = $accountFactory;
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

    public function add(AccountDto $dto): Account
    {
        $account = $this->accountFactory->create(
            $dto->id,
            $dto->userId,
            $dto->name,
            new AccountType(AccountType::CREDIT_CARD),
            $dto->currencyId,
            $dto->balance,
            $dto->icon
        );
        $this->accountRepository->save($account);

        return $account;
    }

    public function delete(Id $id): void
    {
        $this->accountRepository->delete($id);
    }
}
