<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Account;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\AccountType;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\AccessDeniedException;
use App\Domain\Factory\AccountFactoryInterface;
use App\Domain\Factory\AccountOptionsFactoryInterface;
use App\Domain\Repository\AccountOptionsRepositoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Service\Dto\AccountDto;
use App\Domain\Service\Dto\AccountPositionDto;
use DateTimeInterface;
use Throwable;

class AccountService implements AccountServiceInterface
{
    private AccountRepositoryInterface $accountRepository;
    private AccountFactoryInterface $accountFactory;
    private TransactionServiceInterface $transactionService;
    private AccountOptionsFactoryInterface $accountOptionsFactory;
    private AccountOptionsRepositoryInterface $accountOptionsRepository;
    private AntiCorruptionServiceInterface $antiCorruptionService;
    private FolderRepositoryInterface $folderRepository;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        AccountFactoryInterface $accountFactory,
        TransactionServiceInterface $transactionService,
        AccountOptionsFactoryInterface $accountOptionsFactory,
        AccountOptionsRepositoryInterface $accountOptionsRepository,
        AntiCorruptionServiceInterface $antiCorruptionService,
        FolderRepositoryInterface $folderRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->accountFactory = $accountFactory;
        $this->transactionService = $transactionService;
        $this->accountOptionsFactory = $accountOptionsFactory;
        $this->accountOptionsRepository = $accountOptionsRepository;
        $this->antiCorruptionService = $antiCorruptionService;
        $this->folderRepository = $folderRepository;
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
                $position = count($this->accountRepository->getAvailableForUserId($dto->userId));
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
            $this->accountOptionsRepository->save($accountOptions);

            $folder = $this->folderRepository->get($dto->folderId);
            if (!$folder->getUserId()->isEqual($dto->userId)) {
                throw new AccessDeniedException();
            }
            $folder->addAccount($account);
            $this->folderRepository->save($folder);

            $this->antiCorruptionService->commit();
        } catch (Throwable $exception) {
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

    public function updateBalance(
        Id $accountId,
        float $balance,
        DateTimeInterface $updatedAt,
        ?string $comment = ''
    ): ?Transaction {
        $account = $this->accountRepository->get($accountId);
        if ((string)$account->getBalance() === (string)$balance) {
            return null;
        }

        return $this->transactionService->updateBalance(
            $accountId,
            $account->getBalance() - $balance,
            $updatedAt,
            (string)$comment
        );
    }

    public function orderAccounts(Id $userId, AccountPositionDto ...$changes): void
    {
        $this->antiCorruptionService->beginTransaction();
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
                if (!$accountFound) {
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
            $this->accountOptionsRepository->save(...$tmpOptions);
            $this->folderRepository->save(...$folders);
            $this->antiCorruptionService->commit();
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback();
            throw $exception;
        }
    }
}
