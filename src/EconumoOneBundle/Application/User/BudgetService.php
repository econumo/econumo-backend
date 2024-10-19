<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User;

use App\EconumoOneBundle\Application\Exception\ValidationException;
use App\EconumoOneBundle\Application\User\Dto\UpdateBudgetV1RequestDto;
use App\EconumoOneBundle\Application\User\Dto\UpdateBudgetV1ResultDto;
use App\EconumoOneBundle\Application\User\Assembler\UpdateBudgetV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\NotFoundException;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\UserServiceInterface;

readonly class BudgetService
{
    public function __construct(
        private UpdateBudgetV1ResultAssembler $updateBudgetV1ResultAssembler,
        private UserServiceInterface $userService,
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function updateBudget(
        UpdateBudgetV1RequestDto $dto,
        Id $userId
    ): UpdateBudgetV1ResultDto {
        try {
            $this->userService->updateBudget($userId, new Id($dto->value));
            $user = $this->userRepository->get($userId);
            return $this->updateBudgetV1ResultAssembler->assemble($dto, $user);
        } catch (NotFoundException) {
            throw new ValidationException('Plan not found');
        }
    }
}
