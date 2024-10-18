<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\BudgetFolder;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\BudgetFolderMismatchException;
use App\EconumoOneBundle\Domain\Repository\BudgetFolderRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureOrderItemDto;

readonly class BudgetFoldersService
{
    public function __construct(
        private BudgetFolderRepositoryInterface $budgetFolderRepository
    ) {
    }

    /**
     * @param Id $budgetId
     * @param BudgetStructureOrderItemDto[] $affectedFolders
     * @return void
     */
    public function orderFolders(Id $budgetId, array $affectedFolders): void
    {
        $budgetFolders = $this->budgetFolderRepository->getByBudgetId($budgetId);
        $tmpAffectedFolders = [];
        foreach ($affectedFolders as $folder) {
            $tmpAffectedFolders[$folder->id->getValue()] = $folder;
        }

        foreach ($budgetFolders as $budgetFolder) {
            if (!array_key_exists($budgetFolder->getId()->getValue(), $tmpAffectedFolders)) {
                continue;
            }
            if (!$budgetFolder->getBudget()->getId()->isEqual($budgetId)) {
                throw new BudgetFolderMismatchException();
            }

            $budgetFolder->updatePosition($tmpAffectedFolders[$budgetFolder->getId()->getValue()]->position);
        }

        usort($budgetFolders, function (BudgetFolder $a, BudgetFolder $b) {
            return $a->getPosition() <=> $b->getPosition();
        });

        $position = 0;
        foreach ($budgetFolders as $budgetFolder) {
            $budgetFolder->updatePosition($position++);
        }

        $this->budgetFolderRepository->save($budgetFolders);
    }
}
