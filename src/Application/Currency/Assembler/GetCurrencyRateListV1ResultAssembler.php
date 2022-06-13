<?php

declare(strict_types=1);

namespace App\Application\Currency\Assembler;

use App\Application\Currency\Dto\GetCurrencyRateListV1RequestDto;
use App\Application\Currency\Dto\GetCurrencyRateListV1ResultDto;
use App\Domain\Entity\CurrencyRate;

class GetCurrencyRateListV1ResultAssembler
{
    private CurrencyRateToDtoV1ResultAssembler $currencyRateToDtoV1ResultAssembler;

    public function __construct(CurrencyRateToDtoV1ResultAssembler $currencyRateToDtoV1ResultAssembler)
    {
        $this->currencyRateToDtoV1ResultAssembler = $currencyRateToDtoV1ResultAssembler;
    }

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
            $result->items[] = $this->currencyRateToDtoV1ResultAssembler->assemble($currencyRate);
        }

        return $result;
    }
}
