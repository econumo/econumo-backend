<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Assembler\CreateAccountV1ResultAssembler;
use App\Application\Account\Assembler\DeleteAccountV1ResultAssembler;
use App\Application\Account\Dto\CreateAccountV1RequestDto;
use App\Application\Account\Dto\CreateAccountV1ResultDto;
use App\Application\Account\Dto\DeleteAccountV1RequestDto;
use App\Application\Account\Dto\DeleteAccountV1ResultDto;
use App\Application\Account\Dto\UpdateAccountV1RequestDto;
use App\Application\Account\Dto\UpdateAccountV1ResultDto;
use App\Application\Account\Assembler\UpdateAccountV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\AccountServiceInterface;
use App\Domain\Service\Dto\AccountDto;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccountService
{
    private CreateAccountV1ResultAssembler $createAccountV1ResultAssembler;
    private DeleteAccountV1ResultAssembler $deleteAccountV1ResultAssembler;
    private AccountServiceInterface $accountService;
    private UpdateAccountV1ResultAssembler $updateAccountV1ResultAssembler;
    private AccountRepositoryInterface $accountRepository;
    private AccountAccessServiceInterface $accountAccessService;
    private TranslatorInterface $translator;

    public function __construct(
        CreateAccountV1ResultAssembler $createAccountV1ResultAssembler,
        AccountServiceInterface $accountService,
        DeleteAccountV1ResultAssembler $deleteAccountV1ResultAssembler,
        UpdateAccountV1ResultAssembler $updateAccountV1ResultAssembler,
        AccountRepositoryInterface $accountRepository,
        AccountAccessServiceInterface $accountAccessService,
        TranslatorInterface $translator
    ) {
        $this->createAccountV1ResultAssembler = $createAccountV1ResultAssembler;
        $this->accountService = $accountService;
        $this->deleteAccountV1ResultAssembler = $deleteAccountV1ResultAssembler;
        $this->updateAccountV1ResultAssembler = $updateAccountV1ResultAssembler;
        $this->accountRepository = $accountRepository;
        $this->accountAccessService = $accountAccessService;
        $this->translator = $translator;
    }

    public function createAccount(
        CreateAccountV1RequestDto $dto,
        Id $userId
    ): CreateAccountV1ResultDto {
        $accountDto = new AccountDto();
        $accountDto->userId = $userId;
        $accountDto->name = $dto->name;
        $accountDto->currencyId = new Id($dto->currencyId);
        $accountDto->balance = $dto->balance;
        $accountDto->icon = $dto->icon;

        $account = $this->accountService->create($accountDto);
        return $this->createAccountV1ResultAssembler->assemble($dto, $userId, $account);
    }

    public function deleteAccount(
        DeleteAccountV1RequestDto $dto,
        Id $userId
    ): DeleteAccountV1ResultDto {
        $accountId = new Id($dto->id);
        if (!$this->accountAccessService->canDeleteAccount($userId, $accountId)) {
            throw new AccessDeniedException();
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
            throw new AccessDeniedException();
        }
        $this->accountService->update($userId, $accountId, $dto->name, $dto->icon);
        $updatedAt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->updatedAt);
        $transaction = $this->accountService->updateBalance($accountId, $dto->balance, $updatedAt, $this->translator->trans('account.correction.message'));
        $account = $this->accountRepository->get($accountId);
        return $this->updateAccountV1ResultAssembler->assemble($dto, $userId, $account, $transaction);
    }
}
