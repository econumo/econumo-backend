<?php

declare(strict_types=1);

namespace App\Application\Currency\Assembler;

use App\Application\Currency\Dto\GetCurrencyRateListV1RequestDto;
use App\Application\Currency\Dto\GetCurrencyRateListV1ResultDto;
use App\Domain\Entity\CurrencyRate;

class GetCurrencyRateListV1ResultAssembler
{
    public function __construct(private readonly CurrencyRateToDtoV1ResultAssembler $currencyRateToDtoV1ResultAssembler)
    {
    }

    /**
     * @param array|CurrencyRate[] $currencyRates
     */
    public function assemble(
        GetCurrencyRateListV1RequestDto $dto,
        array $currencyRates
    ): GetCurrencyRateListV1ResultDto {
        $result = new GetCurrencyRateListV1ResultDto();
        $result->items = [];
        foreach ($currencyRates as $currencyRate) {
            $result->items[] = $this->currencyRateToDtoV1ResultAssembler->assemble($currencyRate);
        }

        return $result;
    }
}
