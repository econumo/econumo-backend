<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\UserName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\UserRole;

readonly class BudgetUserAccessDto
{
    public function __construct(
        public Id $id,
        public UserName $name,
        public string $avatar,
        public UserRole $role,
        public bool $isAccepted
    ) {
    }
}
