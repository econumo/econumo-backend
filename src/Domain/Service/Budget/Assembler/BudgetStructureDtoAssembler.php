<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Assembler;

use App\Domain\Entity\Budget;
use App\Domain\Entity\BudgetEntityOption;
use App\Domain\Entity\BudgetEnvelope;
use App\Domain\Entity\BudgetFolder;
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
use App\Domain\Service\Currency\CurrencyConvertorInterface;
use App\Domain\Service\Currency\Dto\CurrencyConvertorDto;

readonly class BudgetStructureDtoAssembler
{
    public function __construct(
        private BudgetEnvelopeRepositoryInterface $budgetEnvelopeRepository,
        private BudgetFolderRepositoryInterface $budgetFolderRepository,
        private BudgetStructureFolderDtoAssembler $budgetStructureFolderDtoAssembler,
        private CurrencyConvertorInterface $currencyConvertor,
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
        $elementsOptions = $this->getElementOptions($budget->getId());
        $folders = [];
        foreach ($this->getFolders($budget->getId()) as $folder) {
            $folders[] = $this->budgetStructureFolderDtoAssembler->assemble($folder);
        }

        $toConvert = [];
        $categoryUsed = [];
        $envelopes = $this->getEnvelopes($budget->getId());
        foreach ($envelopes as $envelope) {
            $type = BudgetEntityType::envelope();
            $index = sprintf('%s-%s', $envelope->getId()->getValue(), $type->getAlias());
            $currencyId = ($elementsOptions[$index] ?? null)?->getCurrency()?->getId() ?? $budget->getCurrencyId();
            $folderId = ($elementsOptions[$index] ?? null)?->getFolder()?->getId();
            $position = ($elementsOptions[$index] ?? null)?->getPosition() ?? 0;
            $budgeted = $elementsAmounts[$index]?->budgeted ?? .0;
            $budgetedBefore = $elementsAmounts[$index]?->budgetedBefore ?? .0;
            $currenciesSpent = $elementsAmounts[$index]?->currenciesSpent ?? [];
            $currenciesSpentBefore = $elementsAmounts[$index]?->currenciesSpentBefore ?? [];
            $children = [];
            foreach ($envelope->getCategories() as $category) {
                if (!array_key_exists($category->getId()->getValue(), $categoryUsed)) {
                    $subIndex = sprintf('%s-%s', $category->getId()->getValue(), BudgetEntityType::category()->getAlias());
                    $children[$subIndex] = [
                        'id' => $elementsAmounts[$subIndex]?->entityId,
                        'type' => BudgetEntityType::category(),
                        'name' => $category->getName(),
                        'icon' => $category->getIcon(),
                        'isArchived' => $category->isArchived(),
                        'currenciesSpent' => $elementsAmounts[$subIndex]?->currenciesSpent ?? [],
                        'currenciesSpentBefore' => $elementsAmounts[$subIndex]?->currenciesSpentBefore ?? []
                    ];
                    foreach ($children[$subIndex]['currenciesSpent'] as $spent) {
                        $toConvert[sprintf('spent-%s', $subIndex)][] = new CurrencyConvertorDto(
                            $spent->periodStart,
                            $spent->periodEnd,
                            $spent->currencyId,
                            $currencyId,
                            $spent->amount
                        );
                    }
                    foreach ($children[$subIndex]['currenciesSpentBefore'] as $spentBefore) {
                        $toConvert[sprintf('spent-before-%s', $subIndex)][] = new CurrencyConvertorDto(
                            $spentBefore->periodStart,
                            $spentBefore->periodEnd,
                            $spentBefore->currencyId,
                            $currencyId,
                            $spentBefore->amount
                        );
                    }
                    $categoryUsed[$category->getId()->getValue()] = $category->getId()->getValue();
                }
            }
            $item = [
                'id' => $envelope->getId(),
                'type' => $type,
                'name' => $envelope->getName(),
                'icon' => $envelope->getIcon(),
                'currencyId' => $currencyId,
                'isArchived' => $envelope->isArchived(),
                'folderId' => $folderId,
                'position' => $position,
                'budgeted' => $budgeted,
                'budgetedBefore' => $budgetedBefore,
                'currenciesSpent' => $currenciesSpent,
                'currenciesSpentBefore' => $currenciesSpentBefore,
                'children' => $children,
            ];
            if (!$envelope->isArchived() || count($currenciesSpent) || count($currenciesSpentBefore) || $budgeted != 0 || $budgetedBefore != 0 || count($children) > 0) {
                $elements[] = $item;
            }
        }

        foreach ($budgetFilters->tags as $tag) {
            $type = BudgetEntityType::tag();
            $index = sprintf('%s-%s', $tag->getId()->getValue(), $type->getAlias());
            $currencyId = ($elementsOptions[$index] ?? null)?->getCurrency()?->getId() ?? $budget->getCurrencyId();
            $folderId = ($elementsOptions[$index] ?? null)?->getFolder()?->getId();
            $position = ($elementsOptions[$index] ?? null)?->getPosition() ?? 0;
            $budgeted = $elementsAmounts[$index]?->budgeted ?? .0;
            $budgetedBefore = $elementsAmounts[$index]?->budgetedBefore ?? .0;
            $currenciesSpent = $elementsAmounts[$index]?->currenciesSpent ?? [];
            $currenciesSpentBefore = $elementsAmounts[$index]?->currenciesSpentBefore ?? [];
            $children = [];
            foreach ($elementsAmounts as $elementsAmount) {
                if ($elementsAmount->tagId && $elementsAmount->tagId->isEqual($tag->getId())) {
                    $category = $budgetFilters->categories[$elementsAmount->entityId->getValue()];
                    $subIndex = sprintf('%s-%s', $category->getId()->getValue(), $type->getAlias());
                    $children[$subIndex] = [
                        'id' => $elementsAmount->entityId,
                        'type' => BudgetEntityType::category(),
                        'name' => $category->getName(),
                        'icon' => $category->getIcon(),
                        'isArchived' => $category->isArchived(),
                        'currenciesSpent' => $elementsAmount->currenciesSpent,
                        'currenciesSpentBefore' => $elementsAmount->currenciesSpentBefore
                    ];
                    foreach ($elementsAmount->currenciesSpent as $spent) {
                        $toConvert[sprintf('spent-%s', $subIndex)][] = new CurrencyConvertorDto(
                            $spent->periodStart,
                            $spent->periodEnd,
                            $spent->currencyId,
                            $currencyId,
                            $spent->amount
                        );
                    }
                    foreach ($elementsAmount->currenciesSpentBefore as $spentBefore) {
                        $toConvert[sprintf('spent-before-%s', $subIndex)][] = new CurrencyConvertorDto(
                            $spentBefore->periodStart,
                            $spentBefore->periodEnd,
                            $spentBefore->currencyId,
                            $currencyId,
                            $spentBefore->amount
                        );
                    }
                }
            }
            $item = [
                'id' => $tag->getId(),
                'type' => $type,
                'name' => $tag->getName(),
                'icon' => $tag->getIcon(),
                'currencyId' => $currencyId,
                'isArchived' => $tag->isArchived(),
                'folderId' => $folderId,
                'position' => $position,
                'budgeted' => $budgeted,
                'budgetedBefore' => $budgetedBefore,
                'currenciesSpent' => $currenciesSpent,
                'currenciesSpentBefore' => $currenciesSpentBefore,
                'children' => $children
            ];
            if (!$tag->isArchived() || count($currenciesSpent) || count($currenciesSpentBefore) || $budgeted != 0 || $budgetedBefore != 0 || count($children) > 0) {
                $elements[$index] = $item;
                foreach ($currenciesSpent as $spent) {
                    $toConvert[sprintf('spent-%s', $index)][] = new CurrencyConvertorDto(
                        $spent->periodStart,
                        $spent->periodEnd,
                        $spent->currencyId,
                        $currencyId,
                        $spent->amount
                    );
                }
                foreach ($currenciesSpentBefore as $spentBefore) {
                    $toConvert[sprintf('spent-before-%s', $index)][] = new CurrencyConvertorDto(
                        $spentBefore->periodStart,
                        $spentBefore->periodEnd,
                        $spentBefore->currencyId,
                        $currencyId,
                        $spentBefore->amount
                    );
                }
            }
        }


        foreach ($budgetFilters->categories as $category) {
            if ($category->getType()->isIncome()) {
                continue;
            }
            if (array_key_exists($category->getId()->getValue(), $categoryUsed)) {
                continue;
            }
            $type = BudgetEntityType::category();
            $index = sprintf('%s-%s', $category->getId()->getValue(), $type->getAlias());
            $currencyId = ($elementsOptions[$index] ?? null)?->getCurrency()?->getId() ?? $budget->getCurrencyId();
            $folderId = ($elementsOptions[$index] ?? null)?->getFolder()?->getId();
            $position = ($elementsOptions[$index] ?? null)?->getPosition() ?? 0;
            $budgeted = $elementsAmounts[$index]?->budgeted ?? .0;
            $budgetedBefore = $elementsAmounts[$index]?->budgetedBefore ?? .0;
            $currenciesSpent = $elementsAmounts[$index]?->currenciesSpent ?? [];
            $currenciesSpentBefore = $elementsAmounts[$index]?->currenciesSpentBefore ?? [];
            $item = [
                'id' => $category->getId(),
                'type' => $type,
                'name' => $category->getName(),
                'icon' => $category->getIcon(),
                'currencyId' => $currencyId,
                'isArchived' => $category->isArchived(),
                'folderId' => $folderId,
                'position' => $position,
                'budgeted' => $budgeted,
                'budgetedBefore' => $budgetedBefore,
                'currenciesSpent' => $currenciesSpent,
                'currenciesSpentBefore' => $currenciesSpentBefore,
                'children' => []
            ];
            if (!$category->isArchived() || count($currenciesSpent) || count($currenciesSpentBefore) || $budgeted != 0 || $budgetedBefore != 0) {
                $elements[$index] = $item;
                foreach ($currenciesSpent as $spent) {
                    $toConvert[sprintf('spent-%s', $index)][] = new CurrencyConvertorDto(
                        $spent->periodStart,
                        $spent->periodEnd,
                        $spent->currencyId,
                        $currencyId,
                        $spent->amount
                    );
                }
                foreach ($currenciesSpentBefore as $spentBefore) {
                    $toConvert[sprintf('spent-before-%s', $index)][] = new CurrencyConvertorDto(
                        $spentBefore->periodStart,
                        $spentBefore->periodEnd,
                        $spentBefore->currencyId,
                        $currencyId,
                        $spentBefore->amount
                    );
                }
            }
        }

        $result = [];
        $amounts = $this->currencyConvertor->bulkConvert($toConvert);
        foreach ($elements as $index => $element) {
            $spent = $amounts[sprintf('spent-%s', $index)] ?? .0;
            $spentBefore = $amounts[sprintf('spent-before-%s', $index)] ?? .0;
            $children = [];
            foreach ($element['children'] as $subIndex => $subElement) {
                $subElementSpent = round($amounts[sprintf('spent-%s', $subIndex)] ?? .0, 2);
                $subElementSpentBefore = round($amounts[sprintf('spent-before-%s', $subIndex)] ?? .0, 2);
                $spent += $subElementSpent;
                $spentBefore += $subElementSpentBefore;
                if (!$subElement['isArchived'] || $subElementSpent != 0) {
                    $children[] = new BudgetStructureChildElementDto(
                        $subElement['id'],
                        $subElement['type'],
                        $subElement['name'],
                        $subElement['icon'],
                        $subElement['isArchived'],
                        $subElementSpent,
                        $subElement['currenciesSpent']
                    );
                }
            }
            $available = $element['budgetedBefore'] - $spentBefore;
            $result[] = new BudgetStructureParentElementDto(
                $element['id'],
                $element['type'],
                $element['name'],
                $element['icon'],
                $element['currencyId'],
                $element['isArchived'],
                $element['folderId'],
                $element['position'],
                round($element['budgeted'], 2),
                round($available, 2),
                round($spent, 2),
                $element['currenciesSpent'],
                $children,
            );
        }

        usort($result, function (BudgetStructureParentElementDto $a, BudgetStructureParentElementDto $b) {
            if ($a->position === $b->position) {
                return 0;
            }
            return ($a->position < $b->position) ? -1 : 1;
        });

        return new BudgetStructureDto($folders, $result);
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

    /**
     * @param Id $budgetId
     * @return BudgetEntityOption[]
     */
    private function getElementOptions(Id $budgetId): array
    {
        $elementsOptions = $this->budgetEntityOptionRepository->getByBudgetId($budgetId);
        $elementsOptionsAssoc = [];
        foreach ($elementsOptions as $item) {
            $index = sprintf('%s-%s', $item->getEntityId()->getValue(), $item->getEntityType()->getAlias());
            $elementsOptionsAssoc[$index] = $item;
        }

        return $elementsOptionsAssoc;
    }
}
