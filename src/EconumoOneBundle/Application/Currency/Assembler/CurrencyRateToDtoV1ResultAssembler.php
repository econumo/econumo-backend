<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Currency\Assembler;

use App\EconumoOneBundle\Application\Currency\Dto\CurrencyRateResultDto;
use App\EconumoOneBundle\Domain\Entity\CurrencyRate;

class CurrencyRateToDtoV1ResultAssembler
{
    public function assemble(CurrencyRate $currencyRate): CurrencyRateResultDto
    {
        $item = new CurrencyRateResultDto();
        $item->currencyId = $currencyRate->getCurrency()->getId()->getValue();
        $item->baseCurrencyId = $currencyRate->getBaseCurrency()->getId()->getValue();
        $item->rate = $currencyRate->getRate();
        $item->updatedAt = $currencyRate->getPublishedAt()->format('Y-m-d H:i:s');
        return $item;
    }
}
