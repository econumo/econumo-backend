<?php

declare(strict_types=1);

namespace App\Application\Connection\Assembler;

use App\Application\Connection\Dto\AcceptInviteV1RequestDto;
use App\Application\Connection\Dto\AcceptInviteV1ResultDto;
use App\Application\User\Assembler\UserToDtoResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Connection\ConnectionServiceInterface;

class AcceptInviteV1ResultAssembler
{
    private UserToDtoResultAssembler $userToDtoResultAssembler;
    private ConnectionServiceInterface $connectionService;

    public function __construct(
        ConnectionServiceInterface $connectionService,
        UserToDtoResultAssembler $userToDtoResultAssembler
    ) {
        $this->userToDtoResultAssembler = $userToDtoResultAssembler;
        $this->connectionService = $connectionService;
    }

    public function assemble(
        AcceptInviteV1RequestDto $dto,
        Id $userId
    ): AcceptInviteV1ResultDto {
        $result = new AcceptInviteV1ResultDto();
        foreach ($this->connectionService->getUserList($userId) as $connection) {
            $result->items[] = $this->userToDtoResultAssembler->assemble($connection);
        }

        return $result;
    }
}
