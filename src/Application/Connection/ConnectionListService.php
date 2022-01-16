<?php

declare(strict_types=1);

namespace App\Application\Connection;

use App\Application\Connection\Dto\GetConnectionListV1RequestDto;
use App\Application\Connection\Dto\GetConnectionListV1ResultDto;
use App\Application\Connection\Assembler\GetConnectionListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Connection\ConnectionAccountServiceInterface;
use App\Domain\Service\Connection\ConnectionServiceInterface;

class ConnectionListService
{
    private GetConnectionListV1ResultAssembler $getConnectionListV1ResultAssembler;
    private ConnectionServiceInterface $connectionService;
    private ConnectionAccountServiceInterface $connectionAccountService;

    public function __construct(
        GetConnectionListV1ResultAssembler $getConnectionListV1ResultAssembler,
        ConnectionServiceInterface $connectionService,
        ConnectionAccountServiceInterface $connectionAccountService
    ) {
        $this->getConnectionListV1ResultAssembler = $getConnectionListV1ResultAssembler;
        $this->connectionService = $connectionService;
        $this->connectionAccountService = $connectionAccountService;
    }

    public function getConnectionList(
        GetConnectionListV1RequestDto $dto,
        Id $userId
    ): GetConnectionListV1ResultDto {
        $sharedWithUserAccess = $this->connectionAccountService->getSharedAccess($userId);
        $connectedUsers = $this->connectionService->getUserList($userId);
        return $this->getConnectionListV1ResultAssembler->assemble($dto, $userId, $sharedWithUserAccess, $connectedUsers);
    }
}
