<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanFolderName;
use App\Domain\Factory\PlanFolderFactoryInterface;
use App\Domain\Repository\PlanFolderRepositoryInterface;

readonly class PlanFolderService implements PlanFolderServiceInterface
{
    public function __construct(
        private PlanFolderRepositoryInterface $planFolderRepository,
        private PlanFolderFactoryInterface $planFolderFactory
    ) {
    }

    public function createFolder(Id $planId, PlanFolderName $name): Id
    {
        $folders = $this->planFolderRepository->getByPlanId($planId);
        $position = 0;
        if (count($folders) > 0) {
            $position = $folders[count($folders) - 1]->getPosition() + 1;
        }

        $folder = $this->planFolderFactory->create($planId, $name, $position);
        $this->planFolderRepository->save([$folder]);

        return $folder->getId();
    }
}
