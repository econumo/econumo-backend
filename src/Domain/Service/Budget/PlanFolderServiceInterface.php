<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanFolderName;

interface PlanFolderServiceInterface
{
    public function createFolder(Id $planId, PlanFolderName $name): Id;
}
