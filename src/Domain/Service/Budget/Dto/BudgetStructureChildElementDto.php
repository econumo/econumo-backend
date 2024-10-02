<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget\Dto;


use App\Domain\Entity\ValueObject\BudgetEntityType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\NameInterface;

readonly class BudgetStructureChildElementDto
{
    public function __construct(
        public Id $id,
        public BudgetEntityType $type,
        public NameInterface $name,
        public Icon $icon,
        public bool $isArchived,
        public float $spent,
        /** @var BudgetEntityAmountSpentDto[] */
        public array $currenciesSpent,
    ) {
    }
}
