<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Factory\BudgetEntityOptionFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetEntityOptionRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetFolderRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureMoveElementDto;

readonly class BudgetElementsService
{
    public function __construct(
        private BudgetEntityOptionRepositoryInterface $entityOptionRepository,
        private BudgetEntityOptionFactoryInterface $budgetEntityOptionFactory,
        private BudgetFolderRepositoryInterface $folderRepository,
    ) {
    }

    /**
     * @param Id $budgetId
     * @param BudgetStructureMoveElementDto[] $elements
     * @return void
     */
    public function moveElements(Id $budgetId, array $elements): void
    {
        $seen = [];
        $options = $this->entityOptionRepository->getByBudgetId($budgetId);
        $updatedOptions = [];
        foreach ($options as $option) {
            if (!array_key_exists($option->getEntityId()->getValue(), $elements)) {
                continue;
            }
            if (array_key_exists($option->getEntityId()->getValue(), $seen)) {
                continue;
            }
            $seen[$option->getEntityId()->getValue()] = true;

            $isUpdated = false;
            $element = $elements[$option->getEntityId()->getValue()];
            if ($element->folderId === null && $option->getFolder() !== null) {
                $option->changeFolder(null);
                $isUpdated = true;
            } elseif ($element->folderId !== null && $option->getFolder() === null) {
                $option->changeFolder($this->folderRepository->getReference($element->folderId));
                $isUpdated = true;
            } elseif ($element->folderId !== null && $option->getFolder() !== null && !$option->getFolder()->getId(
                )->isEqual($element->folderId)) {
                $option->changeFolder($this->folderRepository->getReference($element->folderId));
                $isUpdated = true;
            }

            if ($element->position !== $option->getPosition()) {
                $option->updatePosition($element->position);
                $isUpdated = true;
            }

            if ($isUpdated) {
                $updatedOptions[] = $option;
            }
        }

        foreach ($elements as $element) {
            if (array_key_exists($element->id->getValue(), $elements)) {
                continue;
            }
            $updatedOptions[] = $this->budgetEntityOptionFactory->create(
                $budgetId,
                $element->id,
                $element->type,
                $element->position,
                null,
                $element->folderId
            );
        }

        if ($updatedOptions !== []) {
            $this->entityOptionRepository->save($updatedOptions);
        }
    }
}
