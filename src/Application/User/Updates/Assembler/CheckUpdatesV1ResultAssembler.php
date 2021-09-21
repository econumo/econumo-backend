<?php

declare(strict_types=1);

namespace App\Application\User\Updates\Assembler;

use App\Application\User\Updates\Dto\CheckUpdatesV1RequestDto;
use App\Application\User\Updates\Dto\CheckUpdatesV1ResultDto;

class CheckUpdatesV1ResultAssembler
{
    public function assemble(
        CheckUpdatesV1RequestDto $dto
    ): CheckUpdatesV1ResultDto {
        $result = new CheckUpdatesV1ResultDto();
        $result->profileUpdated = 0;
        $result->accountsUpdated = 0;
        $result->categoriesUpdated = 0;
        $result->payeesUpdated = 0;
        $result->tagsUpdated = 0;
        $result->transactionsUpdated = 0;

        return $result;
    }
}
