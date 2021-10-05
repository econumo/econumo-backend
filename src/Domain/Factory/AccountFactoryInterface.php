<?php
declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\AccountType;
use App\Domain\Entity\ValueObject\Id;

interface AccountFactoryInterface
{
    public function create(Id $id, Id $userId, string $name, AccountType $accountType, Id $currencyId, float $balance, string $icon): Account;
}
