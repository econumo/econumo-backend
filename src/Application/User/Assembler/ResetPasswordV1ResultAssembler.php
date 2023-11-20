<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\ResetPasswordV1RequestDto;
use App\Application\User\Dto\ResetPasswordV1ResultDto;

readonly class ResetPasswordV1ResultAssembler
{
    public function assemble(
        ResetPasswordV1RequestDto $dto
    ): ResetPasswordV1ResultDto {
        return new ResetPasswordV1ResultDto();
    }
}
