<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\AccountResultDto;
use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\CurrencyRepositoryInterface;

class AccountToDtoV1ResultAssembler
{
    private CurrencyRepositoryInterface $currencyRepository;
    private AccountIdToSharedAccessResultAssembler $accountIdToSharedAccessResultAssembler;

    public function __construct(
        CurrencyRepositoryInterface $currencyRepository,
        AccountIdToSharedAccessResultAssembler $accountIdToSharedAccessResultAssembler
    ) {
        $this->currencyRepository = $currencyRepository;
        $this->accountIdToSharedAccessResultAssembler = $accountIdToSharedAccessResultAssembler;
    }

    public function assemble(Id $userId, Account $account): AccountResultDto
    {
        $item = new AccountResultDto();
        $item->id = $account->getId()->getValue();
        $item->ownerUserId = $account->getUserId()->getValue();
        $item->name = $account->getName();
        $item->position = $account->getPosition();
        $item->currencyId = $account->getCurrencyId()->getValue();
        try {
            $currency = $this->currencyRepository->get($account->getCurrencyId());
        } catch (NotFoundException $exception) {
            $currency = null;
        }
        $item->currencySign = $currency !== null ? $currency->getSign() : '';
        $item->currencyAlias = $currency !== null ? $currency->getAlias() : '';
        $item->balance = $account->getBalance();
        $item->type = $account->getType()->getValue();
        $item->icon = $account->getIcon();
        $item->sharedAccess = $this->accountIdToSharedAccessResultAssembler->assemble($account->getId());

        return $item;
    }
}
