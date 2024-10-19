<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\BudgetElementOption;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\BudgetFolderRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\CurrencyRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;

readonly class BudgetElementOptionFactory implements BudgetElementOptionFactoryInterface
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
    ): BudgetElementOption {
        $currency = null;
        if ($currencyId) {
            $currency = $this->currencyRepository->getReference($currencyId);
        }
        $budgetFolder = null;
        if ($folderId) {
            $budgetFolder = $this->budgetFolderRepository->getReference($folderId);
        }
        return new BudgetElementOption(
            $elementId,
            $elementType,
            $this->budgetRepository->getReference($budgetId),
            $currency,
            $budgetFolder,
            $position,
            $this->datetimeService->getCurrentDatetime()
        );
    }

    public function createCategoryOption(
        Id $budgetId,
        Id $categoryId,
        int $position,
        ?Id $currencyId = null,
        ?Id $folderId = null
    ): BudgetElementOption {
        return $this->create(
            $budgetId,
            $categoryId,
            BudgetElementType::category(),
            $position,
            $currencyId,
            $folderId
        );
    }

    public function createTagOption(
        Id $budgetId,
        Id $tagId,
        int $position,
        ?Id $currencyId = null,
        ?Id $folderId = null
    ): BudgetElementOption {
        return $this->create(
            $budgetId,
            $tagId,
            BudgetElementType::tag(),
            $position,
            $currencyId,
            $folderId
        );
    }

    public function createEnvelopeOption(
        Id $budgetId,
        Id $envelopeId,
        int $position,
        ?Id $currencyId = null,
        ?Id $folderId = null
    ): BudgetElementOption {
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
