<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;


use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\NameInterface;

readonly class BudgetStructureParentElementDto
{
    public function __construct(
        public Id $id,
        public BudgetElementType $type,
        public NameInterface $name,
        public Icon $icon,
        public ?Id $ownerId,
        public Id $currencyId,
        public bool $isArchived,
        public ?Id $folderId,
        public int $position,
        public float $budgeted,
        public float $available,
        public float $spent,
        public float $spentInBudgetCurrency,
        /** @var BudgetElementAmountSpentDto[] */
        public array $currenciesSpent,
        /** @var BudgetStructureChildElementDto[] */
        public array $children
    ) {
    }
}
