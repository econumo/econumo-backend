<?php

declare(strict_types=1);

namespace App\Application\Connection\Assembler;

use App\Application\Connection\Dto\AcceptInviteV1RequestDto;
use App\Application\Connection\Dto\AcceptInviteV1ResultDto;

class AcceptInviteV1ResultAssembler
{
    public function assemble(
        AcceptInviteV1RequestDto $dto
    ): AcceptInviteV1ResultDto {
        $result = new AcceptInviteV1ResultDto();
        $result->result = 'test';

        return $result;
    }
}
