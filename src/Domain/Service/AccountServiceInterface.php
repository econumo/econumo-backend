<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ValueObject\Id;

interface AccountServiceInterface
{
    public function isAccountAvailable(Id $userId, Id $accountId): bool;
}
