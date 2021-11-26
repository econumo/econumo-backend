<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\AccountResultDto;
use App\Application\Currency\Assembler\CurrencyIdToDtoV1ResultAssembler;
use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\CurrencyRepositoryInterface;

class AccountToDtoV1ResultAssembler
{
    private CurrencyRepositoryInterface $currencyRepository;
    private AccountIdToSharedAccessResultAssembler $accountIdToSharedAccessResultAssembler;
    private CurrencyIdToDtoV1ResultAssembler $currencyIdToDtoV1ResultAssembler;

    public function __construct(
        CurrencyRepositoryInterface $currencyRepository,
        AccountIdToSharedAccessResultAssembler $accountIdToSharedAccessResultAssembler,
        CurrencyIdToDtoV1ResultAssembler $currencyIdToDtoV1ResultAssembler
    ) {
        $this->currencyRepository = $currencyRepository;
        $this->accountIdToSharedAccessResultAssembler = $accountIdToSharedAccessResultAssembler;
        $this->currencyIdToDtoV1ResultAssembler = $currencyIdToDtoV1ResultAssembler;
    }

    public function assemble(Id $userId, Account $account): AccountResultDto
    {
        $item = new AccountResultDto();
        $item->id = $account->getId()->getValue();
        $item->ownerUserId = $account->getUserId()->getValue();
        $item->name = $account->getName();
        $item->position = $account->getPosition();
        $item->currency = $this->currencyIdToDtoV1ResultAssembler->assemble($account->getCurrencyId());
        $item->balance = $account->getBalance();
        $item->type = $account->getType()->getValue();
        $item->icon = $account->getIcon();
        $item->sharedAccess = $this->accountIdToSharedAccessResultAssembler->assemble($account->getId());

        return $item;
    }
}
