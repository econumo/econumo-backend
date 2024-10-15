<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetFolderName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureFolderDto;

interface FolderServiceInterface
{
    public function create(Id $budgetId, Id $folderId, BudgetFolderName $name): BudgetStructureFolderDto;

    public function delete(Id $folderId): void;
}
