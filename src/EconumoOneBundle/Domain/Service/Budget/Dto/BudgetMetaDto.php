<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;

use App\EconumoOneBundle\Domain\Entity\BudgetAccess;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

readonly class BudgetMetaDto
{
    public function __construct(
        public Id $id,
        public Id $ownerUserId,
        public BudgetName $budgetName,
        public DateTimeInterface $startedAt,
        public ?Id $currencyId, // todo fix
        /** @var BudgetUserAccessDto[] */
        public array $access
    ) {
    }
}