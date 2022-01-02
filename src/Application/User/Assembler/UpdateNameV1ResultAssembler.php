<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\UpdateNameV1RequestDto;
use App\Application\User\Dto\UpdateNameV1ResultDto;

class UpdateNameV1ResultAssembler
{
    public function assemble(
        UpdateNameV1RequestDto $dto,
        string $token
    ): UpdateNameV1ResultDto {
        $result = new UpdateNameV1ResultDto();
        $result->token = $token;

        return $result;
    }
}
