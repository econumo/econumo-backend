<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanFolderName;
use App\Domain\Exception\PlanFolderIsNotEmptyException;
use App\Domain\Factory\PlanFolderFactoryInterface;
use App\Domain\Repository\EnvelopeRepositoryInterface;
use App\Domain\Repository\PlanFolderRepositoryInterface;

readonly class PlanFolderService implements PlanFolderServiceInterface
{
    public function __construct(
        private PlanFolderRepositoryInterface $planFolderRepository,
        private PlanFolderFactoryInterface $planFolderFactory,
        private EnvelopeRepositoryInterface $envelopeRepository
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

    public function deleteFolder(Id $folderId): void
    {
        $folder = $this->planFolderRepository->get($folderId);
        $envelopes = $this->envelopeRepository->getByPlanId($folder->getPlan()->getId());
        foreach ($envelopes as $envelope) {
            if ($envelope->getFolder() && $envelope->getFolder()->getId()->isEqual($folderId)) {
                throw new PlanFolderIsNotEmptyException();
            }
        }
        $this->planFolderRepository->delete($folder);
    }

    public function updateFolder(Id $folderId, PlanFolderName $name): void
    {
        $folder = $this->planFolderRepository->get($folderId);
        $folder->updateName($name);
        $this->planFolderRepository->save([$folder]);
    }
}
