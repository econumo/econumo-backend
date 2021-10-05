<?php

declare(strict_types=1);

namespace App\Application\Currency\Collection\Assembler;

use App\Application\Currency\Collection\Dto\GetCollectionV1RequestDto;
use App\Application\Currency\Collection\Dto\GetCollectionV1ResultDto;
use App\Domain\Entity\Currency;

class GetCollectionV1ResultAssembler
{
    private CurrencyToDtoV1ResultAssembler $currencyToDtoV1ResultAssembler;

    public function __construct(CurrencyToDtoV1ResultAssembler $currencyToDtoV1ResultAssembler)
    {
        $this->currencyToDtoV1ResultAssembler = $currencyToDtoV1ResultAssembler;
    }

    /**
     * @param GetCollectionV1RequestDto $dto
     * @param Currency[] $currencies
     * @return GetCollectionV1ResultDto
     */
    public function assemble(
        GetCollectionV1RequestDto $dto,
        array $currencies
    ): GetCollectionV1ResultDto {
        $result = new GetCollectionV1ResultDto();
        foreach ($currencies as $currency) {
            $result->items[] = $this->currencyToDtoV1ResultAssembler->assemble($currency);
        }

        return $result;
    }
}
