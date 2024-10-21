<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\Category;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\AccessDeniedException;
use App\EconumoOneBundle\Domain\Factory\BudgetElementOptionFactoryInterface;
use App\EconumoOneBundle\Domain\Factory\BudgetEnvelopeFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementAmountRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementOptionRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetEnvelopeRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\AntiCorruptionServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Assembler\BudgetDtoAssembler;
use App\EconumoOneBundle\Domain\Service\Budget\Assembler\BudgetFiltersDtoAssembler;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetEnvelopeDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureChildElementDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureParentElementDto;

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
        private BudgetDtoAssembler $budgetDtoAssembler,
    ) {
    }

    public function create(Id $budgetId, BudgetEnvelopeDto $envelope): BudgetStructureParentElementDto
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $budget = $this->budgetRepository->get($budgetId);
            /** @var Category[] $envelopeCategoriesMap */
            $envelopeCategoriesMap = [];
            if ($envelope->categories !== []) {
                $budgetUserIds = $this->budgetFiltersDtoAssembler->getBudgetUserIds($budget);
                $availableCategories = $this->budgetFiltersDtoAssembler->getCategories($budgetUserIds);
                foreach ($envelope->categories as $category) {
                    if (!array_key_exists($category->getValue(), $availableCategories)) {
                        throw new AccessDeniedException();
                    }
                    $envelopeCategoriesMap[$category->getValue()] = $availableCategories[$category->getValue()];
                }

                $updatedOptions = [];
                $budgetElementsOptions = $this->budgetElementOptionRepository->getByBudgetId($budgetId);
                foreach ($budgetElementsOptions as $budgetElementOption) {
                    if (!$budgetElementOption->getElementType()->isCategory()) {
                        continue;
                    }
                    if (!array_key_exists($budgetElementOption->getElementId()->getValue(), $envelopeCategoriesMap)) {
                        continue;
                    }
                    $budgetElementOption->unsetPosition();
                    $budgetElementOption->changeFolder(null);
                    $updatedOptions[] = $budgetElementOption;
                }
                $this->budgetElementOptionRepository->save($updatedOptions);

                // @todo migrate amounts ?

                $this->budgetEnvelopeRepository->deleteAssociationsWithCategories($budgetId, $envelope->categories);
            }


            $this->budgetElementsService->shiftElements($budgetId, $envelope->folderId, $envelope->position);
            $envelopeEntity = $this->budgetEnvelopeFactory->create(
                $budgetId,
                $envelope->id,
                $envelope->name,
                $envelope->icon,
                $envelope->categories
            );
            $this->budgetEnvelopeRepository->save([$envelopeEntity]);

            $envelopeCurrencyId = $envelope->currencyId;
            if ($envelopeCurrencyId !== null && $budget->getCurrencyId()->isEqual($envelope->currencyId)) {
                $envelopeCurrencyId = null;
            }
            $envelopeOptions = $this->budgetElementOptionFactory->createEnvelopeOption(
                $budgetId,
                $envelope->id,
                $envelope->position,
                $envelopeCurrencyId,
                $envelope->folderId
            );
            $this->budgetElementOptionRepository->save([$envelopeOptions]);

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $exception;
        }

        $children = [];
        if ($envelope->categories !== []) {
            foreach ($envelopeCategoriesMap as $category) {
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
        }
        return new BudgetStructureParentElementDto(
            $envelope->id,
            BudgetElementType::envelope(),
            $envelope->name,
            $envelope->icon,
            null,
            $envelope->currencyId,
            false,
            $envelope->folderId,
            $envelope->position,
            0.0,
            0.0,
            0.0,
            [],
            $children
        );
    }
}
