<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Connection;

use App\EconumoOneBundle\Application\Connection\Dto\DeleteConnectionV1RequestDto;
use App\EconumoOneBundle\Application\Connection\Dto\DeleteConnectionV1ResultDto;
use App\EconumoOneBundle\Application\Connection\Assembler\DeleteConnectionV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Connection\ConnectionServiceInterface;

readonly class ConnectionService
{
    public function __construct(
        private DeleteConnectionV1ResultAssembler $deleteConnectionV1ResultAssembler,
        private ConnectionServiceInterface $connectionService
    ) {
    }

    public function deleteConnection(
        DeleteConnectionV1RequestDto $dto,
        Id $userId
    ): DeleteConnectionV1ResultDto {
        $this->connectionService->delete($userId, new Id($dto->id));
        return $this->deleteConnectionV1ResultAssembler->assemble($dto);
    }
}
