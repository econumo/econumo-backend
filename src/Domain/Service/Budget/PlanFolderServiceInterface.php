<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanFolderName;
use App\Domain\Exception\PlanFolderIsNotEmptyException;

interface PlanFolderServiceInterface
{
    public function createFolder(Id $planId, PlanFolderName $name): Id;

    /**
     * @param Id $folderId
     * @return void
     * @throws PlanFolderIsNotEmptyException
     */
    public function deleteFolder(Id $folderId): void;
}
