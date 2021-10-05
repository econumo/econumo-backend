<?php

declare(strict_types=1);

namespace App\Application\Account\Collection\Assembler;

use App\Application\Account\Collection\Dto\ReorderCollectionV1RequestDto;
use App\Application\Account\Collection\Dto\ReorderCollectionV1ResultDto;

class ReorderCollectionV1ResultAssembler
{
    public function assemble(
        ReorderCollectionV1RequestDto $dto
    ): ReorderCollectionV1ResultDto {
        $result = new ReorderCollectionV1ResultDto();
        $result->result = 'test';

        return $result;
    }
}
