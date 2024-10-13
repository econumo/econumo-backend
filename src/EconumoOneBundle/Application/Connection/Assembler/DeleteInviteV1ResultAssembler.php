<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Connection\Assembler;

use App\EconumoOneBundle\Application\Connection\Dto\DeleteInviteV1RequestDto;
use App\EconumoOneBundle\Application\Connection\Dto\DeleteInviteV1ResultDto;

class DeleteInviteV1ResultAssembler
{
    public function assemble(
        DeleteInviteV1RequestDto $dto
    ): DeleteInviteV1ResultDto {
        return new DeleteInviteV1ResultDto();
    }
}
