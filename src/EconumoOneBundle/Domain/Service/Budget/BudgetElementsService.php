<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\BudgetElementOption;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\InvalidBudgetElementException;
use App\EconumoOneBundle\Domain\Factory\BudgetElementOptionFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementOptionRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetEnvelopeRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetFolderRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Assembler\BudgetFiltersDtoAssembler;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureMoveElementDto;

readonly class BudgetElementsService
{
    public function __construct(
        private BudgetElementOptionRepositoryInterface $elementOptionRepository,
        private BudgetElementOptionFactoryInterface $budgetEntityOptionFactory,
        private BudgetFolderRepositoryInterface $folderRepository,
        private BudgetFiltersDtoAssembler $budgetFiltersDtoAssembler,
        private BudgetEnvelopeRepositoryInterface $envelopeRepository,
        private BudgetRepositoryInterface $budgetRepository,
    ) {
    }

    /**
     * @param Id $budgetId
     * @param BudgetStructureMoveElementDto[] $elements
     * @return void
     */
    public function moveElements(Budget $budget, array $elements): void
    {
        $seen = [];
        $options = $this->elementOptionRepository->getByBudgetId($budget->getId());
        $updatedOptions = [];
        foreach ($options as $option) {
            if (!array_key_exists($option->getElementId()->getValue(), $elements)) {
                continue;
            }
            if (array_key_exists($option->getElementId()->getValue(), $seen)) {
                continue;
            }
            $seen[$option->getElementId()->getValue()] = true;

            $isUpdated = false;
            $element = $elements[$option->getElementId()->getValue()];
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

        /** @var BudgetElementOption[] $newOptions */
        $newOptions = [];
        $needTags = false;
        $needCategories = false;
        $needEnvelopes = false;
        foreach ($elements as $element) {
            if (array_key_exists($element->id->getValue(), $elements)) {
                continue;
            }
            $newOptions[$element->type->getAlias()][$element->id->getValue(
            )] = $this->budgetEntityOptionFactory->create(
                $budget->getId(),
                $element->id,
                $element->type,
                $element->position,
                null,
                $element->folderId
            );
            if ($element->type->isCategory()) {
                $needCategories = true;
            } elseif ($element->type->isTag()) {
                $needTags = true;
            } elseif ($element->type->isEnvelope()) {
                $needEnvelopes = true;
            }
        }

        if ($newOptions !== []) {
            if ($needCategories || $needTags) {
                $budgetUserIds = $this->budgetFiltersDtoAssembler->getBudgetUserIds($budget);
                if (array_key_exists(BudgetElementType::category()->getAlias(), $newOptions)) {
                    $budgetCategories = $this->budgetFiltersDtoAssembler->getCategories($budgetUserIds);
                    foreach ($newOptions[BudgetElementType::category()->getAlias()] as $option) {
                        if (!array_key_exists($option->getElementId()->getValue(), $budgetCategories)) {
                            throw new InvalidBudgetElementException();
                        }
                        $updatedOptions[] = $option;
                    }
                }
                if (array_key_exists(BudgetElementType::tag()->getAlias(), $newOptions)) {
                    $budgetTags = $this->budgetFiltersDtoAssembler->getTags($budgetUserIds);
                    foreach ($newOptions[BudgetElementType::tag()->getAlias()] as $option) {
                        if (!array_key_exists($option->getElementId()->getValue(), $budgetTags)) {
                            throw new InvalidBudgetElementException();
                        }
                        $updatedOptions[] = $option;
                    }
                }
            }

            if ($needEnvelopes) {
                $envelopes = $this->envelopeRepository->getByBudgetId($budget->getId());
                foreach ($newOptions[BudgetElementType::envelope()->getAlias()] as $option) {
                    $found = false;
                    foreach ($envelopes as $envelope) {
                        if ($envelope->getId()->isEqual($option->getElementId())) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        throw new InvalidBudgetElementException();
                    }
                    $updatedOptions[] = $option;
                }
            }
        }

        if ($updatedOptions !== []) {
            $this->elementOptionRepository->save($updatedOptions);
        }

        $this->updateElementsOrder($budget->getId());
    }

    public function updateElementsOrder(Id $budgetId): void
    {
        $folders = $this->folderRepository->getByBudgetId($budgetId);
        $options = $this->elementOptionRepository->getByBudgetId($budgetId);

        $optionsAssoc = [];
        foreach ($options as $option) {
            $index = sprintf('%s_%s', $option->getElementId()->getValue(), $option->getElementType()->getAlias());
            $optionsAssoc[$index] = $option;
        }

        $seen = [];
        $budget = $this->budgetRepository->get($budgetId);
        $budgetUserIds = $this->budgetFiltersDtoAssembler->getBudgetUserIds($budget);
        $categories = $this->budgetFiltersDtoAssembler->getCategories($budgetUserIds);
        $categoryType = BudgetElementType::category()->getAlias();
        foreach ($categories as $category) {
            $categoryIndex = sprintf('%s_%s', $category->getId()->getValue(), $categoryType);
            $seen[$categoryIndex] = true;
            if (!array_key_exists($categoryIndex, $optionsAssoc)) {
                $optionsAssoc[$categoryIndex] = $this->budgetEntityOptionFactory->createCategoryOption($budgetId, $category->getId(), PHP_INT_MAX);
                $options[] = $optionsAssoc[$categoryIndex];
            }
            if ($category->isArchived()) {
                $optionsAssoc[$categoryIndex]->unsetPosition();
            } elseif ($optionsAssoc[$categoryIndex]->isPositionUnset()) {
                $optionsAssoc[$categoryIndex]->updatePosition(PHP_INT_MAX);
            }
        }

        $tags = $this->budgetFiltersDtoAssembler->getTags($budgetUserIds);
        $tagType = BudgetElementType::tag()->getAlias();
        foreach ($tags as $tag) {
            $tagIndex = sprintf('%s_%s', $tag->getId()->getValue(), $tagType);
            $seen[$tagIndex] = true;
            if (!array_key_exists($tagIndex, $optionsAssoc)) {
                $optionsAssoc[$tagIndex] = $this->budgetEntityOptionFactory->createTagOption($budgetId, $tag->getId(), PHP_INT_MAX);
                $options[] = $optionsAssoc[$tagIndex];
            }
            if ($tag->isArchived()) {
                $optionsAssoc[$tagIndex]->unsetPosition();
            } elseif ($optionsAssoc[$tagIndex]->isPositionUnset()) {
                $optionsAssoc[$tagIndex]->updatePosition(PHP_INT_MAX);
            }
        }

        $envelopes = $this->envelopeRepository->getByBudgetId($budgetId);
        $envelopeType = BudgetElementType::envelope()->getAlias();
        foreach ($envelopes as $envelope) {
            $envelopeIndex = sprintf('%s_%s', $envelope->getId()->getValue(), $envelopeType);
            $seen[$envelopeIndex] = true;
            if (!array_key_exists($envelopeIndex, $optionsAssoc)) {
                $optionsAssoc[$envelopeIndex] = $this->budgetEntityOptionFactory->createEnvelopeOption($budgetId, $envelope->getId(), PHP_INT_MAX);
                $options[] = $optionsAssoc[$envelopeIndex];
            }
            if ($envelope->isArchived()) {
                $optionsAssoc[$envelopeIndex]->unsetPosition();
            } elseif ($optionsAssoc[$envelopeIndex]->isPositionUnset()) {
                $optionsAssoc[$envelopeIndex]->updatePosition(PHP_INT_MAX);
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
        $this->elementOptionRepository->save($options);

        $keysToDelete = array_diff(array_keys($optionsAssoc), array_keys($seen));
        $toDelete = [];
        foreach ($keysToDelete as $optionId) {
            $toDelete[] = $optionsAssoc[$optionId];
        }
        if ($toDelete !== []) {
            $this->elementOptionRepository->delete($toDelete);
        }
    }
}
