<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Currency;

use App\EconumoOneBundle\Application\Currency\Dto\GetCurrencyListV1RequestDto;
use App\EconumoOneBundle\Application\Currency\Dto\GetCurrencyListV1ResultDto;
use App\EconumoOneBundle\Application\Currency\Assembler\GetCurrencyListV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\CurrencyRepositoryInterface;

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
