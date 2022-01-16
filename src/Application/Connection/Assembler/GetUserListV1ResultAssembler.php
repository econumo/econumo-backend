<?php

declare(strict_types=1);

namespace App\Application\Connection\Assembler;

use App\Application\Connection\Dto\GetUserListV1RequestDto;
use App\Application\Connection\Dto\GetUserListV1ResultDto;
use App\Application\User\Assembler\UserToDtoResultAssembler;
use App\Domain\Entity\User;

class GetUserListV1ResultAssembler
{
    private UserToDtoResultAssembler $userToDtoResultAssembler;

    public function __construct(UserToDtoResultAssembler $userToDtoResultAssembler)
    {
        $this->userToDtoResultAssembler = $userToDtoResultAssembler;
    }

    /**
     * @param GetUserListV1RequestDto $dto
     * @param iterable|User[] $connections
     * @return GetUserListV1ResultDto
     */
    public function assemble(
        GetUserListV1RequestDto $dto,
        iterable $connections
    ): GetUserListV1ResultDto {
        $result = new GetUserListV1ResultDto();
        foreach ($connections as $connection) {
            $result->items[] = $this->userToDtoResultAssembler->assemble($connection);
        }

        return $result;
    }
}
