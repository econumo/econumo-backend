<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Account;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\AccountType;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\AccountFactoryInterface;
use App\Domain\Factory\AccountOptionsFactoryInterface;
use App\Domain\Repository\AccountOptionsRepositoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Service\Dto\AccountDto;

class AccountService implements AccountServiceInterface
{
    private AccountRepositoryInterface $accountRepository;
    private AccountFactoryInterface $accountFactory;
    private TransactionServiceInterface $transactionService;
    private AccountOptionsFactoryInterface $accountOptionsFactory;
    private AccountOptionsRepositoryInterface $accountOptionsRepository;
    private AntiCorruptionServiceInterface $antiCorruptionService;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        AccountFactoryInterface $accountFactory,
        TransactionServiceInterface $transactionService,
        AccountOptionsFactoryInterface $accountOptionsFactory,
        AccountOptionsRepositoryInterface $accountOptionsRepository,
        AntiCorruptionServiceInterface $antiCorruptionService
    ) {
        $this->accountRepository = $accountRepository;
        $this->accountFactory = $accountFactory;
        $this->transactionService = $transactionService;
        $this->accountOptionsFactory = $accountOptionsFactory;
        $this->accountOptionsRepository = $accountOptionsRepository;
        $this->antiCorruptionService = $antiCorruptionService;
    }

    public function create(AccountDto $dto): Account
    {
        $this->antiCorruptionService->beginTransaction();
        try {
            $userAccountOptions = $this->accountOptionsRepository->getByUserId($dto->userId);
            $position = 0;
            foreach ($userAccountOptions as $option) {
                if ($option->getPosition() > $position) {
                    $position = $option->getPosition();
                }
            }
            if ($position === 0) {
                $position = count($this->accountRepository->findByUserId($dto->userId));
            }

            $account = $this->accountFactory->create(
                $dto->userId,
                $dto->name,
                new AccountType(AccountType::CREDIT_CARD),
                $dto->currencyId,
                $dto->balance,
                $dto->icon
            );
            $this->accountRepository->save($account);

            $accountOptions = $this->accountOptionsFactory->create($account->getId(), $dto->userId, $position);
            $this->accountOptionsRepository->save($accountOptions);;
            $this->antiCorruptionService->commit();
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback();
            throw $exception;
        }

        return $account;
    }

    public function delete(Id $id): void
    {
        $account = $this->accountRepository->get($id);
        $account->delete();
        $this->accountRepository->save($account);
    }

    public function update(Id $userId, Id $accountId, string $name, string $icon = null): void
    {
        $account = $this->accountRepository->get($accountId);
        $account->updateName($name);
        if ($icon !== null) {
            $account->updateIcon($icon);
        }
        $this->accountRepository->save($account);
    }

    public function updateBalance(Id $accountId, float $balance, \DateTimeInterface $updatedAt, ?string $comment = ''): ?Transaction
    {
        $account = $this->accountRepository->get($accountId);
        if ((string)$account->getBalance() === (string)$balance) {
            return null;
        }

        return $this->transactionService->updateBalance($accountId, $account->getBalance() - $balance, $updatedAt, (string) $comment);
    }
}
