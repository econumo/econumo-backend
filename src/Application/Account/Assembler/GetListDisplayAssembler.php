<?php
declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\AccountDisplayDto;
use App\Application\Account\Dto\GetListDisplayDto;
use App\Domain\Entity\Account\Account;

class GetListDisplayAssembler
{
    /**
     * @param Account[] $accounts
     * @return GetListDisplayDto
     */
    public function assemble(array $accounts): GetListDisplayDto
    {
        $dto = new GetListDisplayDto();
        $dto->items = [];
        foreach ($accounts as $account) {
            $item = new AccountDisplayDto();
            $item->id = $account->getId();
            $item->name = $account->getName();
            $item->position = $account->getPosition();
            $item->currencyId = $account->getCurrencyId()->getValue();
            $item->balance = $account->getBalance();
            $item->type = $account->getType()->getValue();
            $dto->items[] = $item;
        }

        return $dto;
    }
}
