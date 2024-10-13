<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User;

use App\EconumoOneBundle\Application\User\Dto\UpdateNameV1RequestDto;
use App\EconumoOneBundle\Application\User\Dto\UpdateNameV1ResultDto;
use App\EconumoOneBundle\Application\User\Assembler\UpdateNameV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\UserServiceInterface;

class NameService
{
    public function __construct(private readonly UpdateNameV1ResultAssembler $updateNameV1ResultAssembler, private readonly UserServiceInterface $userService, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function updateName(
        UpdateNameV1RequestDto $dto,
        Id $userId
    ): UpdateNameV1ResultDto {
        $this->userService->updateName($userId, $dto->name);
        $user = $this->userRepository->get($userId);
        return $this->updateNameV1ResultAssembler->assemble($dto, $user);
    }
}
