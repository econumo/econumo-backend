<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\System\Assembler;

use App\EconumoOneBundle\Application\System\Dto\ImportCurrencyListV1RequestDto;
use App\EconumoOneBundle\Application\System\Dto\ImportCurrencyListV1ResultDto;

class ImportCurrencyListV1ResultAssembler
{
    public function assemble(
        ImportCurrencyListV1RequestDto $dto
    ): ImportCurrencyListV1ResultDto {
        return new ImportCurrencyListV1ResultDto();
    }
}
