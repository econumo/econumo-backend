<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget\Dto;


use App\Domain\Entity\ValueObject\BudgetEntityType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\NameInterface;

readonly class BudgetStructureParentElementDto
{
    public function __construct(
        public Id $id,
        public BudgetEntityType $type,
        public NameInterface $name,
        public Icon $icon,
        public ?Id $currencyId,
        public bool $isArchived,
        public ?Id $folderId,
        public int $position,
        public float $budgeted,
        public float $available,
        public float $spent,
        /** @var BudgetEntityAmountSpentDto[] */
        public array $currenciesSpent,
        /** @var BudgetStructureChildElementDto[] */
        public array $children
    ) {
    }
}
