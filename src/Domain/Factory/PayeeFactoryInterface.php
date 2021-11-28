<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\Payee;
use App\Domain\Entity\ValueObject\Id;

interface PayeeFactoryInterface
{
    public function create(Id $userId, string $name): Payee;
}
