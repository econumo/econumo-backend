<?php

declare(strict_types=1);

namespace App\Application\Currency\Assembler;

use App\Application\Currency\Dto\CurrencyRateResultDto;
use App\Application\Currency\Dto\GetCurrencyRateListV1RequestDto;
use App\Application\Currency\Dto\GetCurrencyRateListV1ResultDto;
use App\Domain\Entity\CurrencyRate;

class GetCurrencyRateListV1ResultAssembler
{
    /**
     * @param GetCurrencyRateListV1RequestDto $dto
     * @param array|CurrencyRate[] $currencyRates
     * @return GetCurrencyRateListV1ResultDto
     */
    public function assemble(
        GetCurrencyRateListV1RequestDto $dto,
        array $currencyRates
    ): GetCurrencyRateListV1ResultDto {
        $result = new GetCurrencyRateListV1ResultDto();
        $result->items = [];
        foreach ($currencyRates as $currencyRate) {
            $item = new CurrencyRateResultDto();
            $item->currencyId = $currencyRate->getCurrency()->getId()->getValue();
            $item->baseCurrencyId = $currencyRate->getBaseCurrency()->getId()->getValue();
            $item->rate = $currencyRate->getRate();
            $item->updatedAt = $currencyRate->getPublishedAt()->format('Y-m-d H:i:s');
            $result->items[] = $item;
        }

        return $result;
    }
}
