<?php

declare(strict_types=1);

namespace App\Application\Account\Account;

use App\Application\Account\Account\Dto\AddAccountV1RequestDto;
use App\Application\Account\Account\Dto\AddAccountV1ResultDto;
use App\Application\Account\Account\Assembler\AddAccountV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\DomainException;
use App\Domain\Exception\NotFoundException;
use App\Domain\Factory\AccountFactoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Service\AccountServiceInterface;
use App\Domain\Service\Dto\AccountDto;

class AccountService
{
    private AddAccountV1ResultAssembler $addAccountV1ResultAssembler;
    private AccountServiceInterface $accountService;

    public function __construct(
        AddAccountV1ResultAssembler $addAccountV1ResultAssembler,
        AccountServiceInterface $accountService
    ) {
        $this->addAccountV1ResultAssembler = $addAccountV1ResultAssembler;
        $this->accountService = $accountService;
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
}
