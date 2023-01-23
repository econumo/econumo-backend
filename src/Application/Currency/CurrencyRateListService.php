<?php

declare(strict_types=1);

namespace App\Application\Currency;

use App\Application\Currency\Dto\GetCurrencyRateListV1RequestDto;
use App\Application\Currency\Dto\GetCurrencyRateListV1ResultDto;
use App\Application\Currency\Assembler\GetCurrencyRateListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Currency\CurrencyRateServiceInterface;

class CurrencyRateListService
{
    public function __construct(private readonly GetCurrencyRateListV1ResultAssembler $getCurrencyRateListV1ResultAssembler, private readonly CurrencyRateServiceInterface $currencyRateService)
    {
    }

    public function getCurrencyRateList(
        GetCurrencyRateListV1RequestDto $dto,
        Id $userId
    ): GetCurrencyRateListV1ResultDto {
        $rates = $this->currencyRateService->getLatestCurrencyRates();
        return $this->getCurrencyRateListV1ResultAssembler->assemble($dto, $rates);
    }
}
