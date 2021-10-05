<?php

declare(strict_types=1);

namespace App\Application\Account\Account;

use App\Application\Account\Account\Assembler\AddAccountV1ResultAssembler;
use App\Application\Account\Account\Dto\AddAccountV1RequestDto;
use App\Application\Account\Account\Dto\AddAccountV1ResultDto;
use App\Application\Account\Account\Dto\DeleteAccountV1RequestDto;
use App\Application\Account\Account\Dto\DeleteAccountV1ResultDto;
use App\Application\Account\Account\Assembler\DeleteAccountV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\AccountServiceInterface;
use App\Domain\Service\Dto\AccountDto;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccountService
{
    private DeleteAccountV1ResultAssembler $deleteAccountV1ResultAssembler;
    private AddAccountV1ResultAssembler $addAccountV1ResultAssembler;
    private AccountServiceInterface $accountService;

    public function __construct(
        AddAccountV1ResultAssembler $addAccountV1ResultAssembler,
        AccountServiceInterface $accountService,
        DeleteAccountV1ResultAssembler $deleteAccountV1ResultAssembler
    ) {
        $this->addAccountV1ResultAssembler = $addAccountV1ResultAssembler;
        $this->accountService = $accountService;
        $this->deleteAccountV1ResultAssembler = $deleteAccountV1ResultAssembler;
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
        return $this->addAccountV1ResultAssembler->assemble($dto, $account);
    }

    public function deleteAccount(
        DeleteAccountV1RequestDto $dto,
        Id $userId
    ): DeleteAccountV1ResultDto {
        $accountId = new Id($dto->id);
        if (!$this->accountService->isAccountAvailable($userId, $accountId)) {
            throw new AccessDeniedHttpException();
        }

        $this->accountService->delete($accountId);
        return $this->deleteAccountV1ResultAssembler->assemble($dto);
    }
}
