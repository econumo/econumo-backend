<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\CategoryName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\CategoryType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

readonly class BudgetCategoryDto
{
    public function __construct(
        public Id $categoryId,
        public Id $ownerUserId,
        public Id $budgetId,
        public ?Id $budgetFolderId,
        public ?Id $currencyId,
        public CategoryName $name,
        public CategoryType $type,
        public Icon $icon,
        public int $position,
        public bool $isArchived,
    ) {
    }
}