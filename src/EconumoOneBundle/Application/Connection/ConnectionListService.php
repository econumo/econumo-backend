<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Connection;

use App\EconumoOneBundle\Application\Connection\Dto\GetConnectionListV1RequestDto;
use App\EconumoOneBundle\Application\Connection\Dto\GetConnectionListV1ResultDto;
use App\EconumoOneBundle\Application\Connection\Assembler\GetConnectionListV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Connection\ConnectionAccountServiceInterface;
use App\EconumoOneBundle\Domain\Service\Connection\ConnectionServiceInterface;

class ConnectionListService
{
    public function __construct(private readonly GetConnectionListV1ResultAssembler $getConnectionListV1ResultAssembler, private readonly ConnectionServiceInterface $connectionService, private readonly ConnectionAccountServiceInterface $connectionAccountService)
    {
    }

    public function getConnectionList(
        GetConnectionListV1RequestDto $dto,
        Id $userId
    ): GetConnectionListV1ResultDto {
        $receivedAccountAccess = $this->connectionAccountService->getReceivedAccountAccess($userId);
        $issuedAccountAccess = $this->connectionAccountService->getIssuedAccountAccess($userId);
        $connectedUsers = $this->connectionService->getUserList($userId);
        return $this->getConnectionListV1ResultAssembler->assemble($dto, $userId, $receivedAccountAccess, $issuedAccountAccess, $connectedUsers);
    }
}
