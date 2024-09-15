<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Dto;

use App\Domain\Entity\ValueObject\CategoryName;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\TagName;

readonly class BudgetTagDto
{
    public function __construct(
        public Id $tagId,
        public Id $ownerUserId,
        public Id $budgetId,
        public ?Id $budgetFolderId,
        public ?Id $currencyId,
        public TagName $name,
        public Icon $icon,
        public int $position,
        public bool $isArchived,
    ) {
    }
}