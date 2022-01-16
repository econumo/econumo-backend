<?php

declare(strict_types=1);

namespace App\Application\Connection\Assembler;

use App\Application\Connection\Dto\DeleteConnectionV1RequestDto;
use App\Application\Connection\Dto\DeleteConnectionV1ResultDto;

class DeleteConnectionV1ResultAssembler
{
    public function assemble(
        DeleteConnectionV1RequestDto $dto
    ): DeleteConnectionV1ResultDto {
        return new DeleteConnectionV1ResultDto();
    }
}
