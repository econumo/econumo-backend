<?php

declare(strict_types=1);

namespace App\Application\Transaction\Transaction\Assembler;

use App\Application\Transaction\Transaction\Dto\CreateTransactionV1RequestDto;
use App\Application\Transaction\Transaction\Dto\CreateTransactionV1ResultDto;

class CreateTransactionV1ResultAssembler
{
    public function assemble(
        CreateTransactionV1RequestDto $dto
    ): CreateTransactionV1ResultDto {
        $result = new CreateTransactionV1ResultDto();
        $result->result = 'test';

        return $result;
    }
}
