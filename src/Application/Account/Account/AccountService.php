<?php

declare(strict_types=1);

namespace App\Application\Account\Account;

use App\Application\Account\Account\Assembler\AddAccountV1ResultAssembler;
use App\Application\Account\Account\Assembler\DeleteAccountV1ResultAssembler;
use App\Application\Account\Account\Dto\AddAccountV1RequestDto;
use App\Application\Account\Account\Dto\AddAccountV1ResultDto;
use App\Application\Account\Account\Dto\DeleteAccountV1RequestDto;
use App\Application\Account\Account\Dto\DeleteAccountV1ResultDto;
use App\Application\Account\Account\Dto\UpdateAccountV1RequestDto;
use App\Application\Account\Account\Dto\UpdateAccountV1ResultDto;
use App\Application\Account\Account\Assembler\UpdateAccountV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\AccountServiceInterface;
use App\Domain\Service\Dto\AccountDto;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccountService
{
    private DeleteAccountV1ResultAssembler $deleteAccountV1ResultAssembler;
    private AddAccountV1ResultAssembler $addAccountV1ResultAssembler;
    private AccountServiceInterface $accountService;
    private UpdateAccountV1ResultAssembler $updateAccountV1ResultAssembler;
    private AccountRepositoryInterface $accountRepository;
    private AccountAccessServiceInterface $accountAccessService;

    public function __construct(
        AddAccountV1ResultAssembler $addAccountV1ResultAssembler,
        AccountServiceInterface $accountService,
        DeleteAccountV1ResultAssembler $deleteAccountV1ResultAssembler,
        UpdateAccountV1ResultAssembler $updateAccountV1ResultAssembler,
        AccountRepositoryInterface $accountRepository,
        AccountAccessServiceInterface $accountAccessService
    ) {
        $this->addAccountV1ResultAssembler = $addAccountV1ResultAssembler;
        $this->accountService = $accountService;
        $this->deleteAccountV1ResultAssembler = $deleteAccountV1ResultAssembler;
        $this->updateAccountV1ResultAssembler = $updateAccountV1ResultAssembler;
        $this->accountRepository = $accountRepository;
        $this->accountAccessService = $accountAccessService;
    }

    public function addAccount(
        AddAccountV1RequestDto $dto,
        Id $userId
    ): AddAccountV1ResultDto {
        $accountDto = new AccountDto();
        $accountDto->userId = $userId;
        $accountDto->id = new Id($dto->id);
        $accountDto->name = $dto->name;
        $accountDto->currencyId = new Id($dto->currencyId);
        $accountDto->balance = $dto->balance;
        $accountDto->icon = $dto->icon;

        $account = $this->accountService->add($accountDto);
        return $this->addAccountV1ResultAssembler->assemble($dto, $userId, $account);
    }

    public function deleteAccount(
        DeleteAccountV1RequestDto $dto,
        Id $userId
    ): DeleteAccountV1ResultDto {
        $accountId = new Id($dto->id);
        if (!$this->accountAccessService->canDeleteAccount($userId, $accountId)) {
            throw new AccessDeniedHttpException();
        }

        $this->accountService->delete($accountId);
        return $this->deleteAccountV1ResultAssembler->assemble($dto);
    }

    public function updateAccount(
        UpdateAccountV1RequestDto $dto,
        Id $userId
    ): UpdateAccountV1ResultDto {
        $accountId = new Id($dto->id);
        if (!$this->accountAccessService->canUpdateAccount($userId, $accountId)) {
            throw new AccessDeniedHttpException();
        }
        $this->accountService->update($accountId, $dto->name, $dto->icon);
        $updatedAt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->updatedAt);
        $transaction = $this->accountService->updateBalance($accountId, $dto->balance, $updatedAt, $dto->comment);
        $account = $this->accountRepository->get($accountId);
        return $this->updateAccountV1ResultAssembler->assemble($dto, $userId, $account, $transaction);
    }
}
