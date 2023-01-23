<?php

declare(strict_types=1);

namespace App\Application\Currency;

use App\Application\Currency\Dto\GetCurrencyListV1RequestDto;
use App\Application\Currency\Dto\GetCurrencyListV1ResultDto;
use App\Application\Currency\Assembler\GetCurrencyListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CurrencyRepositoryInterface;

class CurrencyListService
{
    public function __construct(private readonly GetCurrencyListV1ResultAssembler $getCurrencyListV1ResultAssembler, private readonly CurrencyRepositoryInterface $currencyRepository)
    {
    }

    public function getCurrencyList(
        GetCurrencyListV1RequestDto $dto,
        Id $userId
    ): GetCurrencyListV1ResultDto {
        $currencies = $this->currencyRepository->getAll();
        return $this->getCurrencyListV1ResultAssembler->assemble($dto, $currencies);
    }
}
