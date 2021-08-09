<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\ValueObject\Id;

interface UserRepositoryInterface
{
    public function getNextIdentity(): Id;
}
