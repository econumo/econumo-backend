<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetEnvelopeName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

readonly class BudgetEnvelopeDto
{
    public function __construct(
        public Id $id,
        public ?Id $folderId,
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