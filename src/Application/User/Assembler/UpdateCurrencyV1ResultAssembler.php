<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\UpdateCurrencyV1RequestDto;
use App\Application\User\Dto\UpdateCurrencyV1ResultDto;

class UpdateCurrencyV1ResultAssembler
{
    public function assemble(
        UpdateCurrencyV1RequestDto $dto,
        string $token
    ): UpdateCurrencyV1ResultDto {
        $result = new UpdateCurrencyV1ResultDto();
        $result->token = $token;

        return $result;
    }
}
