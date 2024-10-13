<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Currency\Assembler;

use App\EconumoOneBundle\Application\Currency\Dto\CurrencyResultDto;
use App\EconumoOneBundle\Domain\Entity\Currency;

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
