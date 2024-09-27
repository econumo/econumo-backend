<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Assembler;

use App\Domain\Entity\Budget;
use App\Domain\Entity\BudgetEnvelope;
use App\Domain\Entity\BudgetFolder;
use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\BudgetEntityType;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetEntityOptionRepositoryInterface;
use App\Domain\Repository\BudgetEnvelopeRepositoryInterface;
use App\Domain\Repository\BudgetFolderRepositoryInterface;
use App\Domain\Service\Budget\Dto\BudgetEntityAmountDto;
use App\Domain\Service\Budget\Dto\BudgetFiltersDto;
use App\Domain\Service\Budget\Dto\BudgetStructureChildElementDto;
use App\Domain\Service\Budget\Dto\BudgetStructureDto;
use App\Domain\Service\Budget\Dto\BudgetStructureParentElementDto;

readonly class BudgetStructureDtoAssembler
{
    public function __construct(
        private BudgetEnvelopeRepositoryInterface $budgetEnvelopeRepository,
        private BudgetFolderRepositoryInterface $budgetFolderRepository,
        private BudgetStructureFolderDtoAssembler $budgetStructureFolderDtoAssembler,
        private BudgetEntityOptionRepositoryInterface $budgetEntityOptionRepository,
    ) {
    }

    /**
     * @param Budget $budget
     * @param BudgetEntityAmountDto[] $elementsAmounts
     * @param BudgetFiltersDto $budgetFilters
     * @return BudgetStructureDto
     */
    public function assemble(
        Budget $budget,
        array $elementsAmounts,
        BudgetFiltersDto $budgetFilters
    ): BudgetStructureDto {
        $folders = [];
        foreach ($this->getFolders($budget->getId()) as $folder) {
            $folders[] = $this->budgetStructureFolderDtoAssembler->assemble($folder);
        }

        $envelopes = $this->getEnvelopes($budget->getId());
        $elementsOptions = $this->budgetEntityOptionRepository->getByBudgetId($budget->getId());
        $elementsOptionsAssoc = [];
        foreach ($elementsOptions as $item) {
            $index = sprintf('%s-%s', $item->getEntityId()->getValue(), $item->getEntityType()->getAlias());
            $elementsOptionsAssoc[$index] = $item;
        }

        $elements = [];
        $categoryUsed = [];
        foreach ($envelopes as $envelope) {
            $type = BudgetEntityType::envelope();
            $index = sprintf('%s-%s', $envelope->getId()->getValue(), $type->getAlias());
            $currencyId = $elementsOptionsAssoc[$index]?->getCurrency()?->getId() ?? $budget->getCurrencyId();
            $folderId = $elementsOptionsAssoc[$index]?->getFolder()?->getId();
            $position = $elementsOptionsAssoc[$index]?->getPosition() ?? 0;
            $budgeted = $elementsAmounts[$index]?->budget ?? .0;
            $available = $elementsAmounts[$index]?->available ?? .0;
            $spent = $elementsAmounts[$index]?->spent ?? .0;
            $currenciesSpent = $elementsAmounts[$index]?->currenciesSpent ?? [];
            $children = [];
            foreach ($envelope->getCategories() as $category) {
                if (!array_key_exists($category->getId()->getValue(), $categoryUsed)) {
                    $children[] = $this->assembleSubCategory(
                        $budgetFilters->categories[$category->getId()->getValue()]
                    );
                    $categoryUsed[$category->getId()->getValue()] = $category->getId()->getValue();
                    $spent += ($elementsAmounts[sprintf('%s-%s', $category->getId()->getValue(), BudgetEntityType::category()->getAlias())]?->spent ?? .0);
                }
            }
            $item = new BudgetStructureParentElementDto(
                $envelope->getId(),
                $type,
                $envelope->getName(),
                $envelope->getIcon(),
                $currencyId,
                $folderId,
                $position,
                $budgeted,
                $available,
                $spent,
                $currenciesSpent,
                $children
            );
            $elements[] = $item;
        }
        foreach ($budgetFilters->tags as $tag) {
            $type = BudgetEntityType::tag();
            $index = sprintf('%s-%s', $tag->getId()->getValue(), $type->getAlias());
            $currencyId = $elementsOptionsAssoc[$index]?->getCurrency()?->getId() ?? $budget->getCurrencyId();
            $folderId = $elementsOptionsAssoc[$index]?->getFolder()?->getId();
            $position = $elementsOptionsAssoc[$index]?->getPosition() ?? 0;
            $budgeted = $elementsAmounts[$index]?->budget ?? .0;
            $available = $elementsAmounts[$index]?->available ?? .0;
            $spent = $elementsAmounts[$index]?->spent ?? .0;
            $currenciesSpent = $elementsAmounts[$index]?->currenciesSpent ?? [];
            $children = [];
            foreach ($elementsAmounts as $elementsAmount) {
                if ($elementsAmount->tagId && $elementsAmount->tagId->isEqual($tag->getId())) {
                    $spent += $elementsAmount->spent;
                    $children[] = $this->assembleSubCategory(
                        $budgetFilters->categories[$elementsAmount->entityId->getValue()],
                        $elementsAmount->spent,
                        $elementsAmount->currenciesSpent
                    );
                }
            }
            $item = new BudgetStructureParentElementDto(
                $tag->getId(),
                $type,
                $tag->getName(),
                $tag->getIcon(),
                $currencyId,
                $folderId,
                $position,
                $budgeted,
                $available,
                $spent,
                $currenciesSpent,
                $children
            );
            $elements[] = $item;
        }
        foreach ($budgetFilters->categories as $category) {
            if (array_key_exists($category->getId()->getValue(), $categoryUsed)) {
                continue;
            }
            $type = BudgetEntityType::category();
            $index = sprintf('%s-%s', $category->getId()->getValue(), $type->getAlias());
            $currencyId = $elementsOptionsAssoc[$index]?->getCurrency()?->getId() ?? $budget->getCurrencyId();
            $folderId = $elementsOptionsAssoc[$index]?->getFolder()?->getId();
            $position = $elementsOptionsAssoc[$index]?->getPosition() ?? 0;
            $budgeted = $elementsAmounts[$index]?->budget ?? .0;
            $available = $elementsAmounts[$index]?->available ?? .0;
            $spent = $elementsAmounts[$index]?->spent ?? .0;
            $currenciesSpent = $elementsAmounts[$index]?->currenciesSpent ?? [];
            $item = new BudgetStructureParentElementDto(
                $category->getId(),
                $type,
                $category->getName(),
                $category->getIcon(),
                $currencyId,
                $folderId,
                $position,
                $budgeted,
                $available,
                $spent,
                $currenciesSpent,
                []
            );
            $elements[] = $item;
        }

        return new BudgetStructureDto($folders, $elements);
    }

    private function assembleSubCategory(Category $category, float $spent, array $currenciesSpent): BudgetStructureChildElementDto
    {
        return new BudgetStructureChildElementDto(
            $category->getId(),
            BudgetEntityType::category(),
            $category->getName(),
            $category->getIcon(),
            $spent,
            $currenciesSpent
        );
    }

    /**
     * @param Id $budgetId
     * @return BudgetFolder[]
     */
    private function getFolders(Id $budgetId): array
    {
        $folders = [];
        foreach ($this->budgetFolderRepository->getByBudgetId($budgetId) as $folder) {
            $folders[] = $folder;
        }
        return array_values($folders);
    }

    /**
     * @param Id $budgetId
     * @return BudgetEnvelope[]
     */
    private function getEnvelopes(Id $budgetId): array
    {
        $envelopes = [];
        foreach ($this->budgetEnvelopeRepository->getByBudgetId($budgetId) as $envelope) {
            $envelopes[] = $envelope;
        }
        return $envelopes;
    }
}
