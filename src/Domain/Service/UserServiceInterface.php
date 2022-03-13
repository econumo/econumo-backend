<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;

interface UserServiceInterface
{
    public function register(Email $email, string $password, string $name): User;

    public function updateName(Id $userId, string $name): void;

    public function updateCurrency(Id $userId, CurrencyCode $currencyCode): void;
}
