<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanFolderName;
use App\Domain\Exception\PlanFolderIsNotEmptyException;
use App\Domain\Factory\PlanFolderFactoryInterface;
use App\Domain\Repository\EnvelopeRepositoryInterface;
use App\Domain\Repository\PlanFolderRepositoryInterface;
use App\Domain\Service\AntiCorruptionServiceInterface;

readonly class PlanFolderService implements PlanFolderServiceInterface
{
    public function __construct(
        private PlanFolderRepositoryInterface $planFolderRepository,
        private PlanFolderFactoryInterface $planFolderFactory,
        private EnvelopeRepositoryInterface $envelopeRepository,
        private AntiCorruptionServiceInterface $antiCorruptionService
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
        $planId = $folder->getPlan()->getId();
        $envelopes = $this->envelopeRepository->getByPlanId($planId);
        foreach ($envelopes as $envelope) {
            if ($envelope->getFolder() && $envelope->getFolder()->getId()->isEqual($folderId)) {
                throw new PlanFolderIsNotEmptyException();
            }
        }
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $this->planFolderRepository->delete($folder);

            $folders = $this->planFolderRepository->getByPlanId($planId);
            for ($i = 0; $i < count($folders); $i++) {
                $folders[$i]->updatePosition($i);
            }
            $this->planFolderRepository->save($folders);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }
    }

    public function updateFolder(Id $folderId, PlanFolderName $name): void
    {
        $folder = $this->planFolderRepository->get($folderId);
        $folder->updateName($name);
        $this->planFolderRepository->save([$folder]);
    }

    /**
     * @inheritDoc
     */
    public function orderFolders(Id $planId, array $changes): void
    {
        $folders = $this->planFolderRepository->getByPlanId($planId);
        $changed = [];
        foreach ($folders as $folder) {
            foreach ($changes as $change) {
                if ($folder->getId()->isEqual($change->getId())) {
                    $folder->updatePosition($change->position);
                    $changed[] = $folder;
                    break;
                }
            }
        }

        if ($changed === []) {
            return;
        }
        $this->planFolderRepository->save($changed);
    }
}
