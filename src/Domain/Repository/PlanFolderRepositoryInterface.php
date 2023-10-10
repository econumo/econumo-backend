<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\PlanFolder;
use App\Domain\Entity\ValueObject\Id;

interface PlanFolderRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function get(Id $id): PlanFolder;

    /**
     * @return PlanFolder[]
     */
    public function getByPlanId(Id $planId): array;

    /**
     * @param PlanFolder[] $items
     */
    public function save(array $items): void;

    public function delete(PlanFolder $item): void;
}
