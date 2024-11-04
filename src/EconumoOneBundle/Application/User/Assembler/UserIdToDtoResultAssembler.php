<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User\Assembler;

use App\EconumoOneBundle\Application\User\Assembler\UserToDtoResultAssembler;
use App\EconumoOneBundle\Application\User\Dto\UserResultDto;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;

class UserIdToDtoResultAssembler
{
    public function __construct(private readonly UserRepositoryInterface $userRepository, private readonly UserToDtoResultAssembler $userToDtoResultAssembler)
    {
    }

    public function assemble(Id $userId): UserResultDto
    {
        $user = $this->userRepository->get($userId);
        return $this->userToDtoResultAssembler->assemble($user);
    }
}
