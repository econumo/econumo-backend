<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Factory\BudgetElementFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetEnvelopeRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetFolderRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Assembler\BudgetFiltersDtoAssembler;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureMoveElementDto;

readonly class BudgetElementsActionsService
{
    public function __construct(
        private BudgetElementRepositoryInterface $budgetElementRepository,
        private BudgetElementFactoryInterface $budgetElementFactory,
        private BudgetFolderRepositoryInterface $budgetFolderRepository,
        private BudgetFiltersDtoAssembler $budgetFiltersDtoAssembler,
        private BudgetEnvelopeRepositoryInterface $budgetEnvelopeRepository,
        private BudgetRepositoryInterface $budgetRepository,
    ) {
    }

    /**
     * @param Budget $budget
     * @param BudgetStructureMoveElementDto[] $elementsToMove
     * @return void
     */
    public function moveElements(Budget $budget, array $elementsToMove): void
    {
        $seen = [];
        $budgetElements = $this->budgetElementRepository->getByBudgetId($budget->getId());
        $updatedElements = [];
        foreach ($budgetElements as $option) {
            if (!array_key_exists($option->getExternalId()->getValue(), $elementsToMove)) {
                continue;
            }
            if (array_key_exists($option->getExternalId()->getValue(), $seen)) {
                continue;
            }
            $seen[$option->getExternalId()->getValue()] = true;

            $isUpdated = false;
            $element = $elementsToMove[$option->getExternalId()->getValue()];
            if ($element->folderId === null && $option->getFolder() !== null) {
                $option->changeFolder(null);
                $isUpdated = true;
            } elseif ($element->folderId !== null && $option->getFolder() === null) {
                $option->changeFolder($this->budgetFolderRepository->getReference($element->folderId));
                $isUpdated = true;
            } elseif ($element->folderId !== null && $option->getFolder() !== null && !$option->getFolder()->getId(
                )->isEqual($element->folderId)) {
                $option->changeFolder($this->budgetFolderRepository->getReference($element->folderId));
                $isUpdated = true;
            }

            if ($element->position !== $option->getPosition()) {
                $option->updatePosition($element->position);
                $isUpdated = true;
            }

            if ($isUpdated) {
                $updatedElements[] = $option;
            }
        }

        if ($updatedElements !== []) {
            $this->budgetElementRepository->save($updatedElements);
        }

        $this->restoreElementsOrder($budget->getId());
    }

    public function restoreElementsOrder(Id $budgetId): void
    {
        $folders = $this->budgetFolderRepository->getByBudgetId($budgetId);
        $options = $this->budgetElementRepository->getByBudgetId($budgetId);

        $optionsAssoc = [];
        foreach ($options as $option) {
            $index = sprintf('%s_%s', $option->getExternalId()->getValue(), $option->getType()->getAlias());
            $optionsAssoc[$index] = $option;
        }

        $seen = [];
        $budget = $this->budgetRepository->get($budgetId);

        $envelopes = $this->budgetEnvelopeRepository->getByBudgetId($budgetId);
        $envelopeType = BudgetElementType::envelope()->getAlias();
        $childCategoriesMap = [];
        foreach ($envelopes as $envelope) {
            $envelopeIndex = sprintf('%s_%s', $envelope->getId()->getValue(), $envelopeType);
            $seen[$envelopeIndex] = true;
            if (!array_key_exists($envelopeIndex, $optionsAssoc)) {
                $optionsAssoc[$envelopeIndex] = $this->budgetElementFactory->createEnvelopeElement($budgetId, $envelope->getId(), PHP_INT_MAX);
                $options[] = $optionsAssoc[$envelopeIndex];
            }
            if ($envelope->isArchived()) {
                $optionsAssoc[$envelopeIndex]->unsetPosition();
            } elseif ($optionsAssoc[$envelopeIndex]->isPositionUnset()) {
                $optionsAssoc[$envelopeIndex]->updatePosition(PHP_INT_MAX);
            }
            foreach ($envelope->getCategories() as $category) {
                $childCategoriesMap[$category->getId()->getValue()] = $category;
            }
        }

        $budgetUserIds = $this->budgetFiltersDtoAssembler->getBudgetUserIds($budget);
        $categories = $this->budgetFiltersDtoAssembler->getCategories($budgetUserIds);
        $categoryType = BudgetElementType::category()->getAlias();
        foreach ($categories as $category) {
            $categoryIndex = sprintf('%s_%s', $category->getId()->getValue(), $categoryType);
            $seen[$categoryIndex] = true;
            if (!array_key_exists($categoryIndex, $optionsAssoc)) {
                $optionsAssoc[$categoryIndex] = $this->budgetElementFactory->createCategoryElement($budgetId, $category->getId(), PHP_INT_MAX);
                $options[] = $optionsAssoc[$categoryIndex];
            }
            if ($category->isArchived()) {
                $optionsAssoc[$categoryIndex]->unsetPosition();
            } elseif ($optionsAssoc[$categoryIndex]->isPositionUnset()) {
                $optionsAssoc[$categoryIndex]->updatePosition(PHP_INT_MAX);
            }
            if (array_key_exists($category->getId()->getValue(), $childCategoriesMap)) {
                $optionsAssoc[$categoryIndex]->unsetPosition();
                $optionsAssoc[$categoryIndex]->changeFolder(null);
            }
        }

        $tags = $this->budgetFiltersDtoAssembler->getTags($budgetUserIds);
        $tagType = BudgetElementType::tag()->getAlias();
        foreach ($tags as $tag) {
            $tagIndex = sprintf('%s_%s', $tag->getId()->getValue(), $tagType);
            $seen[$tagIndex] = true;
            if (!array_key_exists($tagIndex, $optionsAssoc)) {
                $optionsAssoc[$tagIndex] = $this->budgetElementFactory->createTagElement($budgetId, $tag->getId(), PHP_INT_MAX);
                $options[] = $optionsAssoc[$tagIndex];
            }
            if ($tag->isArchived()) {
                $optionsAssoc[$tagIndex]->unsetPosition();
            } elseif ($optionsAssoc[$tagIndex]->isPositionUnset()) {
                $optionsAssoc[$tagIndex]->updatePosition(PHP_INT_MAX);
            }
        }

        // sorting inside folders
        foreach ($folders as $folder) {
            $position = 0;
            foreach ($options as $option) {
                if (!$option->getFolder() || !$option->getFolder()->getId()->isEqual($folder->getId())) {
                    continue;
                }
                if ($option->isPositionUnset()) {
                    continue;
                }
                $option->updatePosition($position++);
            }
        }

        // sorting inside folders
        $position = 0;
        foreach ($options as $option) {
            if ($option->getFolder() || $option->isPositionUnset()) {
                continue;
            }
            $option->updatePosition($position++);
        }
        $this->budgetElementRepository->save($options);

        $keysToDelete = array_diff(array_keys($optionsAssoc), array_keys($seen));
        $toDelete = [];
        foreach ($keysToDelete as $optionId) {
            $toDelete[] = $optionsAssoc[$optionId];
        }
        if ($toDelete !== []) {
            $this->budgetElementRepository->delete($toDelete);
        }
    }

    public function shiftElements(Id $budgetId, ?Id $folderId, int $startPosition): void
    {
        $options = $this->budgetElementRepository->getByBudgetId($budgetId);
        $position = $startPosition;
        $updated = [];
        foreach ($options as $option) {
            if ($folderId === null && $option->getFolder() !== null) {
                continue;
            }
            if ($folderId !== null && $option->getFolder() === null) {
                continue;
            }
            if ($folderId !== null && $option->getFolder() !== null && !$option->getFolder()->getId()->isEqual($folderId)) {
                continue;
            }
            if ($option->getPosition() < $startPosition) {
                continue;
            }
            $option->updatePosition(++$position);
            $updated[] = $option;
        }

        if ($updated === []) {
            return;
        }

        $this->budgetElementRepository->save($updated);
    }
}
