<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanFolderName;
use App\Domain\Exception\PlanFolderIsNotEmptyException;
use App\Domain\Service\Dto\PositionDto;

interface PlanFolderServiceInterface
{
    public function createFolder(Id $planId, PlanFolderName $name): Id;

    /**
     * @param Id $folderId
     * @return void
     * @throws PlanFolderIsNotEmptyException
     */
    public function deleteFolder(Id $folderId): void;

    public function updateFolder(Id $folderId, PlanFolderName $name): void;

    /**
     * @param Id $planId
     * @param PositionDto[] $changes
     * @return void
     */
    public function orderFolders(Id $planId, array $changes): void;
}
