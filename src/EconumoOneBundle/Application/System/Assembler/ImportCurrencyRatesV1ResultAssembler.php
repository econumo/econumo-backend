<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\System\Assembler;

use App\EconumoOneBundle\Application\System\Dto\ImportCurrencyRatesV1RequestDto;
use App\EconumoOneBundle\Application\System\Dto\ImportCurrencyRatesV1ResultDto;

class ImportCurrencyRatesV1ResultAssembler
{
    public function assemble(
        ImportCurrencyRatesV1RequestDto $dto
    ): ImportCurrencyRatesV1ResultDto {
        return new ImportCurrencyRatesV1ResultDto();
    }
}
