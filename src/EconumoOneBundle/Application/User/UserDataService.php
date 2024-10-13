<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User;

use App\EconumoOneBundle\Application\User\Dto\GetUserDataV1RequestDto;
use App\EconumoOneBundle\Application\User\Dto\GetUserDataV1ResultDto;
use App\EconumoOneBundle\Application\User\Assembler\GetUserDataV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;

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
