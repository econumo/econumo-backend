<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Dto;

use App\Domain\Entity\ValueObject\BudgetEnvelopeName;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;

readonly class BudgetEnvelopeDto
{
    public function __construct(
        public Id $envelopeId,
        public ?Id $budgetFolderId,
        public ?Id $currencyId,
        public BudgetEnvelopeName $name,
        public Icon $icon,
        public int $position,
        public bool $isArchived,
        /** @var Id[] */
        public array $categories,
    ) {
    }
}