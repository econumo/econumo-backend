<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\BudgetEntityOption;
use App\Domain\Entity\ValueObject\BudgetEntityType;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetFolderRepositoryInterface;
use App\Domain\Repository\BudgetRepositoryInterface;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

readonly class BudgetEntityOptionFactory implements BudgetEntityOptionFactoryInterface
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
        Id $entityId,
        BudgetEntityType $budgetEntityType,
        int $position,
        ?Id $currencyId,
        ?Id $folderId
    ): BudgetEntityOption {
        $currency = null;
        if ($currencyId) {
            $currency = $this->currencyRepository->getReference($currencyId);
        }
        $budgetFolder = null;
        if ($folderId) {
            $budgetFolder = $this->budgetFolderRepository->getReference($folderId);
        }
        return new BudgetEntityOption(
            $entityId,
            $budgetEntityType,
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
    ): BudgetEntityOption {
        return $this->create(
            $budgetId,
            $categoryId,
            BudgetEntityType::category(),
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
    ): BudgetEntityOption {
        return $this->create(
            $budgetId,
            $tagId,
            BudgetEntityType::tag(),
            $position,
            $currencyId,
            $folderId
        );
    }

    public function createEnvelopeOption(
        Id $budgetId,
        Id $envelopeId,
        int $position,
        ?Id $currencyId,
        ?Id $folderId
    ): BudgetEntityOption {
        return $this->create(
            $budgetId,
            $envelopeId,
            BudgetEntityType::envelope(),
            $position,
            $currencyId,
            $folderId
        );
    }
}
