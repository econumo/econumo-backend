<?php

declare(strict_types=1);

namespace App\Application\System\Assembler;

use App\Application\System\Dto\ImportCurrencyRatesV1RequestDto;
use App\Application\System\Dto\ImportCurrencyRatesV1ResultDto;

class ImportCurrencyRatesV1ResultAssembler
{
    public function assemble(
        ImportCurrencyRatesV1RequestDto $dto
    ): ImportCurrencyRatesV1ResultDto {
        return new ImportCurrencyRatesV1ResultDto();
    }
}
