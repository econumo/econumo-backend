<?php

declare(strict_types=1);

namespace App\Application\Connection;

use App\Application\Connection\Dto\DeleteConnectionV1RequestDto;
use App\Application\Connection\Dto\DeleteConnectionV1ResultDto;
use App\Application\Connection\Assembler\DeleteConnectionV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Connection\ConnectionServiceInterface;

class ConnectionService
{
    public function __construct(private readonly DeleteConnectionV1ResultAssembler $deleteConnectionV1ResultAssembler, private readonly ConnectionServiceInterface $connectionService)
    {
    }

    public function deleteConnection(
        DeleteConnectionV1RequestDto $dto,
        Id $userId
    ): DeleteConnectionV1ResultDto {
        $this->connectionService->delete($userId, new Id($dto->id));
        return $this->deleteConnectionV1ResultAssembler->assemble($dto);
    }
}
