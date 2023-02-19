<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\User\Dto\UpdateReportPeriodV1RequestDto;
use App\Application\User\Dto\UpdateReportPeriodV1ResultDto;
use App\Application\User\Assembler\UpdateReportPeriodV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\ReportPeriod;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\UserServiceInterface;

class ReportPeriodService
{
    public function __construct(private readonly UpdateReportPeriodV1ResultAssembler $updateReportPeriodV1ResultAssembler, private readonly UserServiceInterface $userService, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function updateReportPeriod(
        UpdateReportPeriodV1RequestDto $dto,
        Id $userId
    ): UpdateReportPeriodV1ResultDto {
        $this->userService->updateReportPeriod($userId, new ReportPeriod($dto->value));
        $user = $this->userRepository->get($userId);
        return $this->updateReportPeriodV1ResultAssembler->assemble($dto, $user);
    }
}
