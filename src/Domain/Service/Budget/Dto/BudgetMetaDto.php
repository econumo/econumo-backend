<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Dto;

use App\Domain\Entity\BudgetAccess;
use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

readonly class BudgetMetaDto
{
    public function __construct(
        public Id $id,
        public Id $ownerUserId,
        public BudgetName $budgetName,
        public DateTimeInterface $startedAt,
        public ?Id $currencyId, // todo fix
        /** @var BudgetAccess[] */
        public array $sharedAccess
    ) {
    }
}