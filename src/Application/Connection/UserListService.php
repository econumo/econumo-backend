<?php

declare(strict_types=1);

namespace App\Application\Connection;

use App\Application\Connection\Dto\GetUserListV1RequestDto;
use App\Application\Connection\Dto\GetUserListV1ResultDto;
use App\Application\Connection\Assembler\GetUserListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Connection\ConnectionServiceInterface;

class UserListService
{
    private GetUserListV1ResultAssembler $getUserListV1ResultAssembler;
    private ConnectionServiceInterface $connectionService;

    public function __construct(
        GetUserListV1ResultAssembler $getUserListV1ResultAssembler,
        ConnectionServiceInterface $connectionService
    ) {
        $this->getUserListV1ResultAssembler = $getUserListV1ResultAssembler;
        $this->connectionService = $connectionService;
    }

    public function getUserList(
        GetUserListV1RequestDto $dto,
        Id $userId
    ): GetUserListV1ResultDto {
        $connections = $this->connectionService->getUserList($userId);
        return $this->getUserListV1ResultAssembler->assemble($dto, $connections);
    }
}
