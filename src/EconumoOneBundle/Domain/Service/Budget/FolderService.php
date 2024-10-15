<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget;

use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetFolderName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Factory\BudgetFolderFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetFolderRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Assembler\BudgetStructureFolderDtoAssembler;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureFolderDto;

readonly class FolderService implements FolderServiceInterface
{
    public function __construct(
        private BudgetFolderFactoryInterface $budgetFolderFactory,
        private BudgetFolderRepositoryInterface $budgetFolderRepository,
        private BudgetStructureFolderDtoAssembler $budgetStructureFolderDtoAssembler,
        private BudgetRepositoryInterface $budgetRepository,
    ) {
    }

    public function create(Id $budgetId, Id $folderId, BudgetFolderName $name): BudgetStructureFolderDto
    {
        $toSave = [];
        $budget = $this->budgetRepository->find($budgetId);
        $newFolder = $this->budgetFolderFactory->create($budget->getId(), $folderId, $name);
        $toSave[] = $newFolder;

        $folders = $this->budgetFolderRepository->getByBudgetId($budgetId);
        $position = 0;
        foreach ($folders as $folder) {
            $position++;
            if ($folder->getPosition() === $position) {
                continue;
            }
            $folder->updatePosition($position);
            $toSave[] = $folder;
        }
        $this->budgetFolderRepository->save($toSave);

        return $this->budgetStructureFolderDtoAssembler->assemble($newFolder);
    }

    public function delete(Id $folderId): void
    {
        $folder = $this->budgetFolderRepository->get($folderId);
        $this->budgetFolderRepository->delete([$folder]);

        $toSave = [];
        $folders = $this->budgetFolderRepository->getByBudgetId($folder->getBudget()->getId());
        $position = 0;
        foreach ($folders as $folder) {
            if ($folder->getPosition() === $position) {
                $position++;
                continue;
            }
            $folder->updatePosition($position);
            $toSave[] = $folder;
            $position++;
        }
        $this->budgetFolderRepository->save($toSave);
    }
}
