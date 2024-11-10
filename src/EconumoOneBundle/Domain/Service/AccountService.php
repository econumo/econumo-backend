<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service;

use App\EconumoOneBundle\Domain\Entity\Account;
use App\EconumoOneBundle\Domain\Entity\Transaction;
use App\EconumoOneBundle\Domain\Entity\ValueObject\AccountName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\AccountType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\AccessDeniedException;
use App\EconumoOneBundle\Domain\Factory\AccountFactoryInterface;
use App\EconumoOneBundle\Domain\Factory\AccountOptionsFactoryInterface;
use App\EconumoOneBundle\Domain\Factory\TransactionFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\AccountOptionsRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\FolderRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\TransactionRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\Dto\AccountDto;
use DateTimeInterface;
use Throwable;

readonly class AccountService implements AccountServiceInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private AccountFactoryInterface $accountFactory,
        private TransactionServiceInterface $transactionService,
        private AccountOptionsFactoryInterface $accountOptionsFactory,
        private AccountOptionsRepositoryInterface $accountOptionsRepository,
        private AntiCorruptionServiceInterface $antiCorruptionService,
        private FolderRepositoryInterface $folderRepository,
        private TransactionFactoryInterface $transactionFactory,
        private TransactionRepositoryInterface $transactionRepository,
        private DatetimeServiceInterface $datetimeService,
    ) {
    }

    public function create(AccountDto $dto): Account
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $userAccountOptions = $this->accountOptionsRepository->getByUserId($dto->userId);
            $position = 0;
            foreach ($userAccountOptions as $option) {
                if ($option->getPosition() > $position) {
                    $position = $option->getPosition();
                }
            }

            if ($position === 0) {
                $position = count($this->accountRepository->getAvailableForUserId($dto->userId));
            }

            $account = $this->accountFactory->create(
                $dto->userId,
                new AccountName($dto->name),
                new AccountType(AccountType::CREDIT_CARD),
                $dto->currencyId,
                new Icon($dto->icon)
            );
            $this->accountRepository->save([$account]);

            $accountOptions = $this->accountOptionsFactory->create($account->getId(), $dto->userId, $position);
            $this->accountOptionsRepository->save([$accountOptions]);

            $folder = $this->folderRepository->get($dto->folderId);
            if (!$folder->getUserId()->isEqual($dto->userId)) {
                throw new AccessDeniedException();
            }

            $folder->addAccount($account);
            $this->folderRepository->save([$folder]);

            if ((string)$dto->balance !== '0') {
                $transaction = $this->transactionFactory->createTransaction(
                    $account->getId(),
                    $dto->balance,
                    $account->getCreatedAt()
                );
                $this->transactionRepository->save([$transaction]);
            }

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }

        return $account;
    }

    public function delete(Id $id): void
    {
        $account = $this->accountRepository->get($id);
        $account->delete();

        $this->accountRepository->save([$account]);
    }

    public function update(Id $userId, Id $accountId, AccountName $name, Icon $icon = null): void
    {
        $account = $this->accountRepository->get($accountId);
        $account->updateName($name);
        if ($icon !== null) {
            $account->updateIcon($icon);
        }

        $this->accountRepository->save([$account]);
    }

    public function updateBalance(
        Id $accountId,
        float $balance,
        DateTimeInterface $updatedAt,
        ?string $comment = ''
    ): ?Transaction {
        $actualBalance = $this->getBalance($accountId);
        if (sprintf('%.2f', $actualBalance) === sprintf('%.2f', $balance)) {
            return null;
        }

        return $this->transactionService->updateBalance(
            $accountId,
            round($actualBalance, 2) - round($balance, 2),
            $updatedAt,
            (string)$comment
        );
    }

    /**
     * @inheritDoc
     */
    public function orderAccounts(Id $userId, array $changes): void
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $accounts = $this->accountRepository->getAvailableForUserId($userId);
            $accountOptions = $this->accountOptionsRepository->getByUserId($userId);
            $folders = $this->folderRepository->getByUserId($userId);

            $tmpOptions = [];
            foreach ($changes as $change) {
                $accountFound = null;
                foreach ($accounts as $account) {
                    if ($change->getId()->isEqual($account->getId())) {
                        $accountFound = $account;
                        break;
                    }
                }

                if (!$accountFound instanceof Account) {
                    continue;
                }

                foreach ($folders as $folder) {
                    if (!$change->getFolderId()->isEqual($folder->getId())) {
                        if ($folder->containsAccount($accountFound)) {
                            $folder->removeAccount($accountFound);
                        }
                    } elseif (!$folder->containsAccount($accountFound)) {
                        $folder->addAccount($accountFound);
                    }
                }

                $optionFound = false;
                foreach ($accountOptions as $accountOption) {
                    if ($change->getId()->isEqual($accountOption->getAccountId())) {
                        $accountOption->updatePosition($change->position);
                        $optionFound = true;
                        $tmpOptions[] = $accountOption;
                        break;
                    }
                }

                if (!$optionFound) {
                    $tmpOptions[] = $this->accountOptionsFactory->create(
                        $change->getId(),
                        $userId,
                        $change->position
                    );
                }
            }

            $this->accountOptionsRepository->save($tmpOptions);
            $this->folderRepository->save($folders);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }
    }

    public function getChanged(Id $userId, DateTimeInterface $lastUpdate): array
    {
        $accounts = $this->accountRepository->getAvailableForUserId($userId);
        $result = [];
        foreach ($accounts as $account) {
            if ($account->getUpdatedAt() > $lastUpdate) {
                $result[] = $account;
            }
        }

        return $result;
    }

    public function getBalance(Id $accountId): float
    {
        $tomorrow = $this->datetimeService->getNextDay();
        return $this->transactionRepository->getAccountBalance(
            $accountId,
            $tomorrow
        );
    }

    public function getAccountsBalance(array $accountsIds): array
    {
        $tomorrow = $this->datetimeService->getNextDay();
        $balances = $this->accountRepository->getAccountsBalancesBeforeDate(
            $accountsIds,
            $tomorrow
        );
        $result = [];
        foreach ($balances as $balance) {
            $result[$balance['account_id']] = round($balance['balance'], 2);
        }

        return $result;
    }
}
