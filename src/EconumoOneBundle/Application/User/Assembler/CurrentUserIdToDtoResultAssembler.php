<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User\Assembler;

use App\EconumoOneBundle\Application\User\Assembler\CurrentUserToDtoResultAssembler;
use App\EconumoOneBundle\Application\User\Dto\CurrentUserResultDto;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;

class CurrentUserIdToDtoResultAssembler
{
    public function __construct(private readonly UserRepositoryInterface $userRepository, private readonly CurrentUserToDtoResultAssembler $currentUserToDtoResultAssembler)
    {
    }

    public function assemble(Id $userId): CurrentUserResultDto
    {
        $user = $this->userRepository->get($userId);
        return $this->currentUserToDtoResultAssembler->assemble($user);
    }
}
