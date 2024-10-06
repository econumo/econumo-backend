<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Budget\Dto;


use App\EconumoBundle\Domain\Entity\ValueObject\BudgetEntityType;
use App\EconumoBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Entity\ValueObject\NameInterface;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetEntityAmountSpentDto;

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
