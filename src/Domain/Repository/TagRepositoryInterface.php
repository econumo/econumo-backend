<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;

interface TagRepositoryInterface
{
    /**
     * @param Id $id
     * @return Tag[]
     */
    public function findByUserId(Id $id): array;
}
