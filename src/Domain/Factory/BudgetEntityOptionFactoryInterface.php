<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\BudgetEntityOption;
use App\Domain\Entity\ValueObject\BudgetEntityType;
use App\Domain\Entity\ValueObject\Id;

interface BudgetEntityOptionFactoryInterface
{
    public function create(
        Id $budgetId,
        Id $entityId,
        BudgetEntityType $budgetEntityType,
        int $position,
        ?Id $currencyId,
        ?Id $folderId
    ): BudgetEntityOption;

    public function createCategoryOption(
        Id $budgetId,
        Id $categoryId,
        int $position,
        ?Id $currencyId = null,
        ?Id $folderId = null
    ): BudgetEntityOption;

    public function createTagOption(
        Id $budgetId,
        Id $tagId,
        int $position,
        ?Id $currencyId = null,
        ?Id $folderId = null
    ): BudgetEntityOption;

    public function createEnvelopeOption(
        Id $budgetId,
        Id $envelopeId,
        int $position,
        ?Id $currencyId,
        ?Id $folderId
    ): BudgetEntityOption;
}
