<?php

declare(strict_types=1);

namespace App\Application\Connection;

use App\Application\Connection\Assembler\DeleteInviteV1ResultAssembler;
use App\Application\Connection\Assembler\GenerateInviteV1ResultAssembler;
use App\Application\Connection\Dto\AcceptInviteV1RequestDto;
use App\Application\Connection\Dto\AcceptInviteV1ResultDto;
use App\Application\Connection\Assembler\AcceptInviteV1ResultAssembler;
use App\Application\Connection\Dto\DeleteInviteV1RequestDto;
use App\Application\Connection\Dto\DeleteInviteV1ResultDto;
use App\Application\Connection\Dto\GenerateInviteV1RequestDto;
use App\Application\Connection\Dto\GenerateInviteV1ResultDto;
use App\Domain\Entity\ValueObject\ConnectionCode;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Connection\ConnectionInviteServiceInterface;

class InviteService
{
    private GenerateInviteV1ResultAssembler $generateInviteV1ResultAssembler;
    private ConnectionInviteServiceInterface $connectionInviteService;
    private DeleteInviteV1ResultAssembler $deleteInviteV1ResultAssembler;
    private AcceptInviteV1ResultAssembler $acceptInviteV1ResultAssembler;

    public function __construct(
        GenerateInviteV1ResultAssembler $generateInviteV1ResultAssembler,
        ConnectionInviteServiceInterface $connectionInviteService,
        DeleteInviteV1ResultAssembler $deleteInviteV1ResultAssembler,
        AcceptInviteV1ResultAssembler $acceptInviteV1ResultAssembler
    ) {
        $this->generateInviteV1ResultAssembler = $generateInviteV1ResultAssembler;
        $this->connectionInviteService = $connectionInviteService;
        $this->deleteInviteV1ResultAssembler = $deleteInviteV1ResultAssembler;
        $this->acceptInviteV1ResultAssembler = $acceptInviteV1ResultAssembler;
    }

    public function generateInvite(
        GenerateInviteV1RequestDto $dto,
        Id $userId
    ): GenerateInviteV1ResultDto {
        $connectionInvite = $this->connectionInviteService->generate($userId);
        return $this->generateInviteV1ResultAssembler->assemble($dto, $connectionInvite);
    }

    public function deleteInvite(
        DeleteInviteV1RequestDto $dto,
        Id $userId
    ): DeleteInviteV1ResultDto {
        $this->connectionInviteService->delete($userId);
        return $this->deleteInviteV1ResultAssembler->assemble($dto);
    }

    public function acceptInvite(
        AcceptInviteV1RequestDto $dto,
        Id $userId
    ): AcceptInviteV1ResultDto {
        $this->connectionInviteService->accept($userId, new ConnectionCode($dto->code));
        return $this->acceptInviteV1ResultAssembler->assemble($dto, $userId);
    }
}
