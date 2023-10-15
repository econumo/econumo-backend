<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\PlanFolder;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanFolderName;
use App\Domain\Repository\PlanFolderRepositoryInterface;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class PlanFolderFactory implements PlanFolderFactoryInterface
{
    public function __construct(
        private PlanFolderRepositoryInterface $planFolderRepository,
        private DatetimeServiceInterface $datetimeService,
        private PlanRepositoryInterface $planRepository,
    )
    {
    }

    public function create(
        Id $planId,
        PlanFolderName $name,
        int $position
    ): PlanFolder {
        return new PlanFolder(
            $this->planFolderRepository->getNextIdentity(),
            $this->planRepository->getReference($planId),
            $name,
            $position,
            $this->datetimeService->getCurrentDatetime()
        );}
}
