<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Transaction\Assembler;

use App\EconumoBundle\Application\Transaction\Dto\ImportTransactionListV1RequestDto;
use App\EconumoBundle\Application\Transaction\Dto\ImportTransactionListV1ResultDto;

readonly class ImportTransactionListV1ResultAssembler
{
    public function assemble(
        ImportTransactionListV1RequestDto $dto
    ): ImportTransactionListV1ResultDto {
        $result = new ImportTransactionListV1ResultDto();
        $result->result = 'test';

        return $result;
    }
}
