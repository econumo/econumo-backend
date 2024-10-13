<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Application\Connection\Assembler;


use App\EconumoOneBundle\Application\Connection\Dto\ConnectionInviteResultDto;
use App\EconumoOneBundle\Domain\Entity\ConnectionInvite;

class ConnectionInviteToDtoResultAssembler
{
    public function assemble(ConnectionInvite $connectionInvite): ConnectionInviteResultDto
    {
        $dto = new ConnectionInviteResultDto();
        $dto->code = $connectionInvite->getCode()->getValue();
        $dto->expiredAt = $connectionInvite->getExpiredAt()->format('Y-m-d H:i:s');

        return $dto;
    }
}
