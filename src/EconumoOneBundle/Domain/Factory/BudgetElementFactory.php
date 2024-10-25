<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\BudgetElement;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\BudgetFolderRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\CurrencyRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;

readonly class BudgetElementFactory implements BudgetElementFactoryInterface
{
    public function __construct(
        private DatetimeServiceInterface $datetimeService,
        private BudgetRepositoryInterface $budgetRepository,
        private CurrencyRepositoryInterface $currencyRepository,
        private BudgetFolderRepositoryInterface $budgetFolderRepository,
    ) {
    }

    public function create(
        Id $budgetId,
        Id $elementId,
        BudgetElementType $elementType,
        int $position,
        ?Id $currencyId,
        ?Id $folderId
    ): BudgetElement {
        $currency = null;
        if ($currencyId) {
            $currency = $this->currencyRepository->getReference($currencyId);
        }
        $budgetFolder = null;
        if ($folderId) {
            $budgetFolder = $this->budgetFolderRepository->getReference($folderId);
        }
        return new BudgetElement(
            $this->budgetRepository->getReference($budgetId),
            $elementId,
            $elementType,
            $currency,
            $budgetFolder,
            $position,
            $this->datetimeService->getCurrentDatetime()
        );
    }

    public function createCategoryElement(
        Id $budgetId,
        Id $categoryId,
        int $position,
        ?Id $currencyId = null,
        ?Id $folderId = null
    ): BudgetElement {
        return $this->create(
            $budgetId,
            $categoryId,
            BudgetElementType::category(),
            $position,
            $currencyId,
            $folderId
        );
    }

    public function createTagElement(
        Id $budgetId,
        Id $tagId,
        int $position,
        ?Id $currencyId = null,
        ?Id $folderId = null
    ): BudgetElement {
        return $this->create(
            $budgetId,
            $tagId,
            BudgetElementType::tag(),
            $position,
            $currencyId,
            $folderId
        );
    }

    public function createEnvelopeElement(
        Id $budgetId,
        Id $envelopeId,
        int $position,
        ?Id $currencyId = null,
        ?Id $folderId = null
    ): BudgetElement {
        return $this->create(
            $budgetId,
            $envelopeId,
            BudgetElementType::envelope(),
            $position,
            $currencyId,
            $folderId
        );
    }
}
