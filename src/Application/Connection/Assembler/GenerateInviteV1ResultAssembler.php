<?php

declare(strict_types=1);

namespace App\Application\Connection\Assembler;

use App\Application\Connection\Dto\GenerateInviteV1RequestDto;
use App\Application\Connection\Dto\GenerateInviteV1ResultDto;
use App\Domain\Entity\ConnectionInvite;

class GenerateInviteV1ResultAssembler
{
    private ConnectionInviteToDtoResultAssembler $connectionInviteToDtoResultAssembler;

    public function __construct(ConnectionInviteToDtoResultAssembler $connectionInviteToDtoResultAssembler)
    {
        $this->connectionInviteToDtoResultAssembler = $connectionInviteToDtoResultAssembler;
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
