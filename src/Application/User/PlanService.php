<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\Exception\ValidationException;
use App\Application\User\Dto\UpdatePlanV1RequestDto;
use App\Application\User\Dto\UpdatePlanV1ResultDto;
use App\Application\User\Assembler\UpdatePlanV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\UserServiceInterface;

readonly class PlanService
{
    public function __construct(
        private UpdatePlanV1ResultAssembler $updatePlanV1ResultAssembler,
        private UserServiceInterface $userService,
        private UserRepositoryInterface $userRepository
    )
    {
    }

    public function updatePlan(
        UpdatePlanV1RequestDto $dto,
        Id $userId
    ): UpdatePlanV1ResultDto {
        try {
            $this->userService->updateDefaultPlan($userId, new Id($dto->value));
            $user = $this->userRepository->get($userId);
            return $this->updatePlanV1ResultAssembler->assemble($dto, $user);
        } catch (NotFoundException) {
            throw new ValidationException('Plan not found');
        }
    }
}
