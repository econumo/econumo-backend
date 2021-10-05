<?php

declare(strict_types=1);


namespace App\Application\Account\Collection\Assembler;


use App\Application\Account\Collection\Dto\AccountItemResultDto;
use App\Domain\Entity\Account;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\CurrencyRepositoryInterface;

class AccountToDtoV1ResultAssembler
{
    private CurrencyRepositoryInterface $currencyRepository;

    public function __construct(CurrencyRepositoryInterface $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function assemble(Account $account): AccountItemResultDto
    {
        $item = new AccountItemResultDto();
        $item->id = $account->getId()->getValue();
        $item->ownerId = $account->getUserId()->getValue();
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
        return $item;
    }
}
