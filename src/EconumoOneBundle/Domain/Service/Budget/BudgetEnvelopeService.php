<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\BudgetElementOption;
use App\EconumoOneBundle\Domain\Entity\BudgetEnvelope;
use App\EconumoOneBundle\Domain\Entity\Category;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\AccessDeniedException;
use App\EconumoOneBundle\Domain\Factory\BudgetElementAmountFactoryInterface;
use App\EconumoOneBundle\Domain\Factory\BudgetElementOptionFactoryInterface;
use App\EconumoOneBundle\Domain\Factory\BudgetEnvelopeFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementAmountRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementOptionRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetEnvelopeRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\CurrencyRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\AntiCorruptionServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Assembler\BudgetFiltersDtoAssembler;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetEnvelopeDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureChildElementDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureParentElementDto;
use DateTime;

readonly class BudgetEnvelopeService implements BudgetEnvelopeServiceInterface
{
    public function __construct(
        private BudgetElementsService $budgetElementsService,
        private BudgetEnvelopeFactoryInterface $budgetEnvelopeFactory,
        private BudgetElementOptionFactoryInterface $budgetElementOptionFactory,
        private BudgetRepositoryInterface $budgetRepository,
        private AntiCorruptionServiceInterface $antiCorruptionService,
        private BudgetEnvelopeRepositoryInterface $budgetEnvelopeRepository,
        private BudgetElementOptionRepositoryInterface $budgetElementOptionRepository,
        private BudgetFiltersDtoAssembler $budgetFiltersDtoAssembler,
        private BudgetElementAmountRepositoryInterface $budgetElementAmountRepository,
        private BudgetElementAmountFactoryInterface $budgetElementAmountFactory,
        private CurrencyRepositoryInterface $currencyRepository,
    ) {
    }

    public function create(
        Id $budgetId,
        BudgetEnvelopeDto $envelope,
        ?Id $folderId = null
    ): BudgetStructureParentElementDto {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $budget = $this->budgetRepository->get($budgetId);
            if ($envelope->categories !== []) {
                $envelopeCategoriesMap = $this->getEligibleCategories($budget, $envelope->categories);
                $this->budgetEnvelopeRepository->deleteAssociationsWithCategories($budgetId, $envelope->categories);
            }

            $this->budgetElementsService->shiftElements($budgetId, $folderId, $envelope->position);
            $envelopeEntity = $this->budgetEnvelopeFactory->create(
                $budgetId,
                $envelope->id,
                $envelope->name,
                $envelope->icon,
                []
            );
            $this->budgetEnvelopeRepository->save([$envelopeEntity]);
            if ($envelope->categories !== []) {
                $this->includeCategoriesToEnvelope($envelopeEntity, $envelopeCategoriesMap);
            }

            $envelopeCurrencyId = $envelope->currencyId;
            if ($envelopeCurrencyId !== null && $budget->getCurrencyId()->isEqual($envelope->currencyId)) {
                $envelopeCurrencyId = null;
            }
            $envelopeOptions = $this->budgetElementOptionFactory->createEnvelopeOption(
                $budgetId,
                $envelope->id,
                $envelope->position,
                $envelopeCurrencyId,
                $folderId
            );
            $this->budgetElementOptionRepository->save([$envelopeOptions]);

            if ($envelope->categories !== []) {
                $this->reassignAmounts($budgetId, $envelopeEntity);
            }

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $exception;
        }
        $this->budgetElementsService->updateElementsOrder($budgetId);

        return $this->assembleEnvelope($envelopeEntity, $envelopeOptions, $budget);
    }

    public function update(Id $budgetId, BudgetEnvelopeDto $envelopeDto): BudgetStructureParentElementDto
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $budget = $this->budgetRepository->get($budgetId);
            $envelope = $this->budgetEnvelopeRepository->get($envelopeDto->id);
            $envelopeType = BudgetElementType::envelope();
            $envelopeOptions = $this->budgetElementOptionRepository->get($budgetId, $envelope->getId(), $envelopeType);

            $envelope->updateName($envelopeDto->name);
            $envelope->updateIcon($envelopeDto->icon);
            $envelope->setArchive($envelopeDto->isArchived);
            $this->budgetEnvelopeRepository->save([$envelope]);

            if ($envelopeDto->currencyId === null
                || ($envelopeDto->currencyId && $budget->getCurrency()->getId()->isEqual($envelopeDto->currencyId))) {
                $envelopeOptions->updateCurrency(null);
            } else {
                $currency = $this->currencyRepository->get($envelopeDto->currencyId);
                $envelopeOptions->updateCurrency($currency);
            }
            $this->budgetElementOptionRepository->save([$envelopeOptions]);

            if ($envelopeDto->categories !== [] || $envelope->getCategories()->count() > 0) {
                $envelopeCategoriesMap = [];
                if ($envelopeDto->categories !== []) {
                    $envelopeCategoriesMap = $this->getEligibleCategories($budget, $envelopeDto->categories);
                }

                $oldCategories = [];
                $categoriesToRemoveFromEnvelope = [];
                foreach ($envelope->getCategories() as $category) {
                    $oldCategories[$category->getId()->getValue()] = $category;
                    if (!array_key_exists($category->getId()->getValue(), $envelopeCategoriesMap)) {
                        $categoriesToRemoveFromEnvelope[$category->getId()->getValue()] = $category;
                    }
                }
                if ($categoriesToRemoveFromEnvelope !== []) {
                    $this->removeCategoriesFromEnvelope($envelope, $categoriesToRemoveFromEnvelope);
                }
                $newCategories = array_diff(array_keys($envelopeCategoriesMap), array_keys($oldCategories));
                $newCategoriesIds = [];
                foreach ($newCategories as $category) {
                    $newCategoriesIds[] = $envelopeCategoriesMap[$category]->getId();
                }
                $this->budgetEnvelopeRepository->deleteAssociationsWithCategories($budgetId, $newCategoriesIds);
                $this->includeCategoriesToEnvelope($envelope, $envelopeCategoriesMap);
            }

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $exception;
        }
        $this->budgetElementsService->updateElementsOrder($budgetId);

        return $this->assembleEnvelope($envelope, $envelopeOptions, $budget);
    }

    private function reassignAmounts(Id $budgetId, BudgetEnvelope $envelope): void
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        $envelopeType = BudgetElementType::envelope();
        $categoryType = BudgetElementType::category();
        try {
            $categoriesIds = [];
            foreach ($envelope->getCategories() as $category) {
                $categoriesIds[] = $category->getId();
            }
            $sourceAmounts = $this->budgetElementAmountRepository
                ->getSummarizedAmountsForElements($budgetId, $categoriesIds, $categoryType);
            $targetAmounts = $this->budgetElementAmountRepository
                ->getByElementIdAndType($budgetId, $envelope->getId(), $envelopeType);

            $updated = [];
            $seen = [];
            foreach ($targetAmounts as $targetAmount) {
                $date = $targetAmount->getPeriod()->format('Y-m-d');
                $seen[] = $date;
                if (array_key_exists($date, $sourceAmounts)) {
                    $targetAmount->updateAmount($targetAmount->getAmount() + $sourceAmounts[$date]);
                    $updated[] = $targetAmount;
                }
            }
            if ($updated !== []) {
                $this->budgetElementAmountRepository->save($updated);
            }

            $keysToCreate = array_diff(array_keys($sourceAmounts), $seen);
            $created = [];
            foreach ($keysToCreate as $key) {
                $created[] = $this->budgetElementAmountFactory->create(
                    $budgetId,
                    $envelope->getId(),
                    $envelopeType,
                    $sourceAmounts[$key],
                    DateTime::createFromFormat('Y-m-d', $key)
                );
            }
            if ($created !== []) {
                $this->budgetElementAmountRepository->save($created);
            }

            $toDelete = [];
            foreach ($envelope->getCategories() as $category) {
                $oldAmounts = $this->budgetElementAmountRepository
                    ->getByElementIdAndType($budgetId, $category->getId(), $categoryType);
                if ($oldAmounts !== []) {
                    $toDelete = array_merge($toDelete, $oldAmounts);
                }
            }
            if ($toDelete !== []) {
                $this->budgetElementAmountRepository->delete($toDelete);
            }

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $exception;
        }
    }

    /**
     * @param BudgetEnvelope $envelope
     * @param BudgetElementOption $envelopeOption
     * @param Budget $budget
     * @return BudgetStructureParentElementDto
     */
    private function assembleEnvelope(
        BudgetEnvelope $envelope,
        BudgetElementOption $envelopeOption,
        Budget $budget
    ): BudgetStructureParentElementDto {
        $children = [];
        foreach ($envelope->getCategories() as $category) {
            $children[] = new BudgetStructureChildElementDto(
                $category->getId(),
                BudgetElementType::category(),
                $category->getName(),
                $category->getIcon(),
                $category->getUserId(),
                $category->isArchived(),
                .0,
                []
            );
        }
        return new BudgetStructureParentElementDto(
            $envelope->getId(),
            $envelopeOption->getElementType(),
            $envelope->getName(),
            $envelope->getIcon(),
            null,
            ($envelopeOption->getCurrency() === null ? $budget->getCurrencyId() : $envelopeOption->getCurrency()->getId(
            )),
            $envelope->isArchived(),
            $envelopeOption->getFolder()?->getId(),
            $envelopeOption->getPosition(),
            0.0,
            0.0,
            0.0,
            [],
            $children
        );
    }

    /**
     * @param Budget $budget
     * @param Id[] $categoriesIds
     * @return Category[]
     */
    private function getEligibleCategories(Budget $budget, array $categoriesIds): array
    {
        $result = [];
        $budgetUserIds = $this->budgetFiltersDtoAssembler->getBudgetUserIds($budget);
        $availableCategories = $this->budgetFiltersDtoAssembler->getCategories($budgetUserIds);
        foreach ($categoriesIds as $category) {
            if (!array_key_exists($category->getValue(), $availableCategories)) {
                throw new AccessDeniedException();
            }
            $result[$category->getValue()] = $availableCategories[$category->getValue()];
        }
        return $result;
    }

    /**
     * @param BudgetEnvelope $envelope
     * @param Category[] $categoriesMap
     * @return void
     */
    private function includeCategoriesToEnvelope(BudgetEnvelope $envelope, array $categoriesMap): void
    {
        if ($categoriesMap === []) {
            return;
        }

        foreach ($categoriesMap as $category) {
            $envelope->addCategory($category);
        }
        $this->budgetEnvelopeRepository->save([$envelope]);

        $updatedOptions = [];
        $budgetElementsOptions = $this->budgetElementOptionRepository->getByBudgetId($envelope->getBudget()->getId());
        foreach ($budgetElementsOptions as $budgetElementOption) {
            if (!$budgetElementOption->getElementType()->isCategory()) {
                continue;
            }
            if (!array_key_exists($budgetElementOption->getElementId()->getValue(), $categoriesMap)) {
                continue;
            }
            $budgetElementOption->unsetPosition();
            $budgetElementOption->changeFolder(null);
            $updatedOptions[] = $budgetElementOption;
        }
        if ($updatedOptions !== []) {
            $this->budgetElementOptionRepository->save($updatedOptions);
        }
    }

    /**
     * @param BudgetEnvelope $envelope
     * @param Category[] $categoriesMap
     * @return void
     */
    private function removeCategoriesFromEnvelope(BudgetEnvelope $envelope, array $categoriesMap): void
    {
        if ($categoriesMap === []) {
            return;
        }

        foreach ($categoriesMap as $category) {
            $envelope->removeCategory($category);
        }

        $toUpdate = [];
        $budgetElementsOptions = $this->budgetElementOptionRepository->getByBudgetId($envelope->getBudget()->getId());
        $envelopeOptions = null;
        foreach ($budgetElementsOptions as $budgetElementOption) {
            if ($budgetElementOption->getElementId()->isEqual($envelope->getId())
                && $budgetElementOption->getElementType()->isEnvelope()) {
                $envelopeOptions = $budgetElementOption;
                break;
            }
        }

        $envelopePosition = 0;
        $envelopeFolder = null;
        $envelopeFolderId = null;
        if ($envelopeOptions !== null) {
            $envelopeFolder = $envelopeOptions->getFolder();
            $envelopeFolderId = $envelopeOptions->getFolder()?->getId();
            $envelopePosition = $envelopeOptions->getPosition();
        }
        $shiftTo = count($categoriesMap);
        $position = $envelopePosition;
        foreach ($budgetElementsOptions as $budgetElementOption) {
            if (!$budgetElementOption->getElementType()->isCategory()) {
                continue;
            }

            if (array_key_exists($budgetElementOption->getElementId()->getValue(), $categoriesMap)) {
                $budgetElementOption->changeFolder($envelopeFolder);
                $budgetElementOption->updatePosition(++$position);
                $toUpdate[] = $budgetElementOption;
            } elseif ($budgetElementOption->getPosition() > $envelopePosition) {
                if (($budgetElementOption->getFolder() === null && $envelopeFolder === null)
                    || ($budgetElementOption->getFolder() !== null && $envelopeFolder !== null
                        && $budgetElementOption->getFolder()->getId()->isEqual($envelopeFolderId))) {
                    $budgetElementOption->updatePosition($budgetElementOption->getPosition() + $shiftTo);
                    $toUpdate[] = $budgetElementOption;
                }
            }
        }
        if ($toUpdate !== []) {
            $this->budgetElementOptionRepository->save($toUpdate);
        }
    }

    public function delete(Id $budgetId, Id $envelopeId): void
    {
        $envelope = $this->budgetEnvelopeRepository->get($envelopeId);
        if (!$envelope->getBudget()->getId()->isEqual($budgetId)) {
            throw new AccessDeniedException('Envelope was not found for this budget');
        }

        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $categoriesMap = [];
            foreach ($envelope->getCategories() as $category) {
                $categoriesMap[$category->getId()->getValue()] = $category;
            }
            $this->removeCategoriesFromEnvelope($envelope, $categoriesMap);

            $type = BudgetElementType::envelope();
            $this->budgetElementAmountRepository->deleteByElementIdAndType($budgetId, $envelope->getId(), $type);
            $this->budgetElementOptionRepository->deleteByElementIdAndType($budgetId, $envelope->getId(), $type);
            $this->budgetEnvelopeRepository->delete([$envelope]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $exception;
        }

        $this->budgetElementsService->updateElementsOrder($budgetId);
    }
}
