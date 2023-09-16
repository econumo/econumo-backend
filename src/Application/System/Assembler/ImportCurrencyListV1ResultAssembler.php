<?php

declare(strict_types=1);

namespace App\Application\System\Assembler;

use App\Application\System\Dto\ImportCurrencyListV1RequestDto;
use App\Application\System\Dto\ImportCurrencyListV1ResultDto;

class ImportCurrencyListV1ResultAssembler
{
    public function assemble(
        ImportCurrencyListV1RequestDto $dto
    ): ImportCurrencyListV1ResultDto {
        return new ImportCurrencyListV1ResultDto();
    }
}
