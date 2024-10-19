<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;


use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\NameInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetElementAmountSpentDto;

readonly class BudgetStructureChildElementDto
{
    public function __construct(
        public Id $id,
        public BudgetElementType $type,
        public NameInterface $name,
        public Icon $icon,
        public Id $ownerId,
        public bool $isArchived,
        public float $spent,
        /** @var BudgetElementAmountSpentDto[] */
        public array $currenciesSpent,
    ) {
    }
}
