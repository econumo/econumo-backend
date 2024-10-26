<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget\Assembler;

use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\BudgetElement;
use App\EconumoOneBundle\Domain\Entity\BudgetEnvelope;
use App\EconumoOneBundle\Domain\Entity\BudgetFolder;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\BudgetElementRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetEnvelopeRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetFolderRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Assembler\BudgetStructureFolderDtoAssembler;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetElementAmountDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetFiltersDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureChildElementDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureParentElementDto;
use App\EconumoOneBundle\Domain\Service\Currency\CurrencyConvertorInterface;
use App\EconumoOneBundle\Domain\Service\Currency\Dto\CurrencyConvertorDto;

readonly class BudgetStructureDtoAssembler
{
    public function __construct(
        private BudgetEnvelopeRepositoryInterface $budgetEnvelopeRepository,
        private BudgetFolderRepositoryInterface $budgetFolderRepository,
        private BudgetStructureFolderDtoAssembler $budgetStructureFolderDtoAssembler,
        private CurrencyConvertorInterface $currencyConvertor,
        private BudgetElementRepositoryInterface $budgetElementRepository,
    ) {
    }

    /**
     * @param Budget $budget
     * @param BudgetElementAmountDto[] $elementsAmounts
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
        $elements = [];
        $envelopes = $this->getEnvelopes($budget->getId());
        foreach ($envelopes as $envelope) {
            $type = BudgetElementType::envelope();
            $index = sprintf('%s-%s', $envelope->getId()->getValue(), $type->getAlias());
            $currencyId = ($elementsOptions[$index] ?? null)?->getCurrency()?->getId() ?? $budget->getCurrencyId();
            $folderId = ($elementsOptions[$index] ?? null)?->getFolder()?->getId();
            $position = ($elementsOptions[$index] ?? null)?->getPosition() ?? BudgetElement::POSITION_UNSET;
            $budgeted = $elementsAmounts[$index]?->budgeted ?? .0;
            $budgetedBefore = $elementsAmounts[$index]?->budgetedBefore ?? .0;
            $currenciesSpent = $elementsAmounts[$index]?->currenciesSpent ?? [];
            $currenciesSpentBefore = $elementsAmounts[$index]?->currenciesSpentBefore ?? [];
            $children = [];
            foreach ($envelope->getCategories() as $category) {
                if (!array_key_exists($category->getId()->getValue(), $categoryUsed)) {
                    $subIndex = sprintf('%s-%s', $category->getId()->getValue(), BudgetElementType::category()->getAlias());
                    $children[$subIndex] = [
                        'id' => $category->getId(),
                        'type' => BudgetElementType::category(),
                        'name' => $category->getName(),
                        'icon' => $category->getIcon(),
                        'ownerId' => $category->getUserId(),
                        'isArchived' => $category->isArchived(),
                        'currenciesSpent' => ($elementsAmounts[$subIndex] ?? null)?->currenciesSpent ?? [],
                        'currenciesSpentBefore' => ($elementsAmounts[$subIndex] ?? null)?->currenciesSpentBefore ?? []
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
                'ownerId' => null,
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
            $type = BudgetElementType::tag();
            $index = sprintf('%s-%s', $tag->getId()->getValue(), $type->getAlias());
            $currencyId = ($elementsOptions[$index] ?? null)?->getCurrency()?->getId() ?? $budget->getCurrencyId();
            $folderId = ($elementsOptions[$index] ?? null)?->getFolder()?->getId();
            $position = ($elementsOptions[$index] ?? null)?->getPosition() ?? BudgetElement::POSITION_UNSET;
            $budgeted = $elementsAmounts[$index]?->budgeted ?? .0;
            $budgetedBefore = $elementsAmounts[$index]?->budgetedBefore ?? .0;
            $currenciesSpent = $elementsAmounts[$index]?->currenciesSpent ?? [];
            $currenciesSpentBefore = $elementsAmounts[$index]?->currenciesSpentBefore ?? [];
            $children = [];
            foreach ($elementsAmounts as $elementsAmount) {
                if ($elementsAmount->tagId && $elementsAmount->tagId->isEqual($tag->getId())) {
                    $category = $budgetFilters->categories[$elementsAmount->elementId->getValue()];
                    $subIndex = sprintf('%s-%s', $category->getId()->getValue(), $type->getAlias());
                    $children[$subIndex] = [
                        'id' => $elementsAmount->elementId,
                        'type' => BudgetElementType::category(),
                        'name' => $category->getName(),
                        'icon' => $category->getIcon(),
                        'ownerId' => $category->getUserId(),
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
                'ownerId' => $tag->getUserId(),
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
            $type = BudgetElementType::category();
            $index = sprintf('%s-%s', $category->getId()->getValue(), $type->getAlias());
            $currencyId = ($elementsOptions[$index] ?? null)?->getCurrency()?->getId() ?? $budget->getCurrencyId();
            $folderId = ($elementsOptions[$index] ?? null)?->getFolder()?->getId();
            $position = ($elementsOptions[$index] ?? null)?->getPosition() ?? BudgetElement::POSITION_UNSET;
            $budgeted = $elementsAmounts[$index]?->budgeted ?? .0;
            $budgetedBefore = $elementsAmounts[$index]?->budgetedBefore ?? .0;
            $currenciesSpent = $elementsAmounts[$index]?->currenciesSpent ?? [];
            $currenciesSpentBefore = $elementsAmounts[$index]?->currenciesSpentBefore ?? [];
            $item = [
                'id' => $category->getId(),
                'type' => $type,
                'name' => $category->getName(),
                'icon' => $category->getIcon(),
                'ownerId' => $category->getUserId(),
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
                    if ($element['type']->isTag() && $subElementSpent == 0) {
                        continue;
                    }
                    $children[] = new BudgetStructureChildElementDto(
                        $subElement['id'],
                        $subElement['type'],
                        $subElement['name'],
                        $subElement['icon'],
                        $subElement['ownerId'],
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
                $element['ownerId'],
                $element['currencyId'],
                $element['isArchived'],
                $element['folderId'],
                $element['position'],
                round($element['budgeted'], 2),
                round($available - $spent, 2),
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
     * @return BudgetElement[]
     */
    private function getElementOptions(Id $budgetId): array
    {
        $elementsOptions = $this->budgetElementRepository->getByBudgetId($budgetId);
        $elementsOptionsAssoc = [];
        foreach ($elementsOptions as $item) {
            $index = sprintf('%s-%s', $item->getExternalId()->getValue(), $item->getType()->getAlias());
            $elementsOptionsAssoc[$index] = $item;
        }

        return $elementsOptionsAssoc;
    }
}
