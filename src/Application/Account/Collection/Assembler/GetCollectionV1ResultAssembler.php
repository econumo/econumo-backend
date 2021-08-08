<?php

declare(strict_types=1);

namespace App\Application\Account\Collection\Assembler;

use App\Application\_Account\Dto\AccountDisplayDto;
use App\Application\Account\Collection\Dto\GetCollectionV1RequestDto;
use App\Application\Account\Collection\Dto\GetCollectionV1ResultDto;
use App\Domain\Entity\Account;

class GetCollectionV1ResultAssembler
{
    /**
     * @param GetCollectionV1RequestDto $dto
     * @param Account[] $accounts
     * @return GetCollectionV1ResultDto
     */
    public function assemble(
        GetCollectionV1RequestDto $dto,
        array $accounts
    ): GetCollectionV1ResultDto {
        $result = new GetCollectionV1ResultDto();
        $result->items = [];
        foreach (array_reverse($accounts) as $account) {
            $item = new AccountDisplayDto();
            $item->id = $account->getId();
            $item->name = $account->getName();
            $item->position = $account->getPosition();
            $item->currencyId = $account->getCurrencyId()->getValue();
            $item->balance = $account->getBalance();
            $item->type = $account->getType()->getValue();
            $result->items[] = $item;
        }

        return $result;
    }
}
