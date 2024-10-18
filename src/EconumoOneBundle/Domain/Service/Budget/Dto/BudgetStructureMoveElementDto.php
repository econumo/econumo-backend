<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetEntityType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

readonly class BudgetStructureMoveElementDto
{
    public function __construct(
        public Id $id,
        public BudgetEntityType $type,
        public int $position,
        public ?Id $folderId,
    ) {
    }
}