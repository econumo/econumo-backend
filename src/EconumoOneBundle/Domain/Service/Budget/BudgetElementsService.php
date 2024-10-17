<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\BudgetEntityOption;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetEntityType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\DomainException;
use App\EconumoOneBundle\Domain\Exception\InvalidBudgetElementException;
use App\EconumoOneBundle\Domain\Factory\BudgetEntityOptionFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetEntityOptionRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetEnvelopeRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetFolderRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Assembler\BudgetFiltersDtoAssembler;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureMoveElementDto;

readonly class BudgetElementsService
{
    public function __construct(
        private BudgetEntityOptionRepositoryInterface $entityOptionRepository,
        private BudgetEntityOptionFactoryInterface $budgetEntityOptionFactory,
        private BudgetFolderRepositoryInterface $folderRepository,
        private BudgetFiltersDtoAssembler $budgetFiltersDtoAssembler,
        private BudgetEnvelopeRepositoryInterface $envelopeRepository,
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
        $options = $this->entityOptionRepository->getByBudgetId($budget->getId());
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

        /** @var BudgetEntityOption[] $newOptions */
        $newOptions = [];
        $needTags = false;
        $needCategories = false;
        $needEnvelopes = false;
        foreach ($elements as $element) {
            if (array_key_exists($element->id->getValue(), $elements)) {
                continue;
            }
            $newOptions[$element->type->getAlias()][$element->id->getValue()] = $this->budgetEntityOptionFactory->create(
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
                if (array_key_exists(BudgetEntityType::category()->getAlias(), $newOptions)) {
                    $budgetCategories = $this->budgetFiltersDtoAssembler->getCategories($budgetUserIds);
                    foreach ($newOptions[BudgetEntityType::category()->getAlias()] as $option) {
                        if (!array_key_exists($option->getEntityId()->getValue(), $budgetCategories)) {
                            throw new InvalidBudgetElementException();
                        }
                        $updatedOptions[] = $option;
                    }
                }
                if (array_key_exists(BudgetEntityType::tag()->getAlias(), $newOptions)) {
                    $budgetTags = $this->budgetFiltersDtoAssembler->getTags($budgetUserIds);
                    foreach ($newOptions[BudgetEntityType::tag()->getAlias()] as $option) {
                        if (!array_key_exists($option->getEntityId()->getValue(), $budgetTags)) {
                            throw new InvalidBudgetElementException();
                        }
                        $updatedOptions[] = $option;
                    }
                }
            }

            if ($needEnvelopes) {
                $envelopes = $this->envelopeRepository->getByBudgetId($budget->getId());
                foreach ($newOptions[BudgetEntityType::envelope()->getAlias()] as $option) {
                    $found = false;
                    foreach ($envelopes as $envelope) {
                        if ($envelope->getId()->isEqual($option->getEntityId())) {
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
            $this->entityOptionRepository->save($updatedOptions);
        }
    }
}
