<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Connection\Assembler;

use App\EconumoOneBundle\Application\Connection\Dto\GenerateInviteV1RequestDto;
use App\EconumoOneBundle\Application\Connection\Dto\GenerateInviteV1ResultDto;
use App\EconumoOneBundle\Application\Connection\Assembler\ConnectionInviteToDtoResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ConnectionInvite;

class GenerateInviteV1ResultAssembler
{
    public function __construct(private readonly ConnectionInviteToDtoResultAssembler $connectionInviteToDtoResultAssembler)
    {
    }

    public function assemble(
        GenerateInviteV1RequestDto $dto,
        ConnectionInvite $connectionInvite
    ): GenerateInviteV1ResultDto {
        $result = new GenerateInviteV1ResultDto();
        $result->item = $this->connectionInviteToDtoResultAssembler->assemble($connectionInvite);

        return $result;
    }
}
