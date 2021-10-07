<?php

declare(strict_types=1);

namespace App\Application\Currency\Collection\Assembler;

use App\Application\Currency\Collection\Dto\CurrencyResultDto;
use App\Domain\Entity\Currency;

class CurrencyToDtoV1ResultAssembler
{
    public function assemble(Currency $currency): CurrencyResultDto
    {
        $dto = new CurrencyResultDto();
        $dto->id = $currency->getId()->getValue();
        $dto->alias = $currency->getAlias();
        $dto->sign = $currency->getSign();
        return $dto;
    }
}