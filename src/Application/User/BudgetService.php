<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\Exception\ValidationException;
use App\Application\User\Dto\UpdateBudgetV1RequestDto;
use App\Application\User\Dto\UpdateBudgetV1ResultDto;
use App\Application\User\Assembler\UpdateBudgetV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\UserServiceInterface;

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
            $this->userService->updateDefaultBudget($userId, new Id($dto->value));
            $user = $this->userRepository->get($userId);
            return $this->updateBudgetV1ResultAssembler->assemble($dto, $user);
        } catch (NotFoundException) {
            throw new ValidationException('Plan not found');
        }
    }
}
