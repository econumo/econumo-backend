<?php

declare(strict_types=1);

namespace App\Application\Account\Collection\Assembler;

use App\Application\Account\Collection\Dto\AccountItemResultDto;
use App\Application\Account\Collection\Dto\GetCollectionV1RequestDto;
use App\Application\Account\Collection\Dto\GetCollectionV1ResultDto;
use App\Domain\Entity\Account;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\CurrencyRepositoryInterface;

class GetCollectionV1ResultAssembler
{
    private CurrencyRepositoryInterface $currencyRepository;

    public function __construct(CurrencyRepositoryInterface $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

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
            $item = new AccountItemResultDto();
            $item->id = $account->getId()->getValue();
            $item->name = $account->getName();
            $item->position = $account->getPosition();
            $item->currencyId = $account->getCurrencyId()->getValue();
            try {
                $item->currencySign = $this->currencyRepository->get($account->getCurrencyId())->getSign();
            } catch (NotFoundException $exception) {
                $item->currencySign = '';
            }
            $item->balance = $account->getBalance();
            $item->type = $account->getType()->getValue();
            $item->icon = $account->getIcon();
            $result->items[] = $item;
        }

        return $result;
    }
}
