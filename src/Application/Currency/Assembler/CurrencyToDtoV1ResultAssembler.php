<?php

declare(strict_types=1);

namespace App\Application\Currency\Assembler;

use App\Application\Currency\Dto\CurrencyResultDto;
use App\Domain\Entity\Currency;

class CurrencyToDtoV1ResultAssembler
{
    public function assemble(Currency $currency): CurrencyResultDto
    {
        $dto = new CurrencyResultDto();
        $dto->id = $currency->getId()->getValue();
        $dto->code = $currency->getCode()->getValue();
        $dto->name = $currency->getName();
        $dto->symbol = $currency->getSymbol();
        return $dto;
    }
}
