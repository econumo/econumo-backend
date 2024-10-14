<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\BudgetFolder;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetFolderName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface BudgetFolderFactoryInterface
{
    /**
     * @param Id $budgetId
     * @param Id $folderId
     * @param BudgetFolderName $name
     * @return BudgetFolder
     */
    public function create(
        Id $budgetId,
        Id $folderId,
        BudgetFolderName $name
    ): BudgetFolder;
}
