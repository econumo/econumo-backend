<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;


use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetFolderName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

readonly class BudgetStructureFolderDto
{
    public function __construct(
        public Id $id,
        public BudgetFolderName $name,
        public int $position
    ) {
    }
}
