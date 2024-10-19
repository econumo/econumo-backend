<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\BudgetElementOption;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface BudgetElementOptionFactoryInterface
{
    public function create(
        Id $budgetId,
        Id $elementId,
        BudgetElementType $elementType,
        int $position,
        ?Id $currencyId,
        ?Id $folderId
    ): BudgetElementOption;

    public function createCategoryOption(
        Id $budgetId,
        Id $categoryId,
        int $position,
        ?Id $currencyId = null,
        ?Id $folderId = null
    ): BudgetElementOption;

    public function createTagOption(
        Id $budgetId,
        Id $tagId,
        int $position,
        ?Id $currencyId = null,
        ?Id $folderId = null
    ): BudgetElementOption;

    public function createEnvelopeOption(
        Id $budgetId,
        Id $envelopeId,
        int $position,
        ?Id $currencyId = null,
        ?Id $folderId = null
    ): BudgetElementOption;
}
