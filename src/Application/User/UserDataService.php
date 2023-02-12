<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\User\Dto\GetUserDataV1RequestDto;
use App\Application\User\Dto\GetUserDataV1ResultDto;
use App\Application\User\Assembler\GetUserDataV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\UserRepositoryInterface;

class UserDataService
{
    public function __construct(private readonly GetUserDataV1ResultAssembler $getUserDataV1ResultAssembler, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function getUserData(
        GetUserDataV1RequestDto $dto,
        Id $userId
    ): GetUserDataV1ResultDto {
        $user = $this->userRepository->get($userId);
        return $this->getUserDataV1ResultAssembler->assemble($dto, $user);
    }
}
