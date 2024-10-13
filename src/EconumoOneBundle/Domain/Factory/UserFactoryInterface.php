<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Email;

interface UserFactoryInterface
{
    public function create(string $name, Email $email, string $password): User;
}
