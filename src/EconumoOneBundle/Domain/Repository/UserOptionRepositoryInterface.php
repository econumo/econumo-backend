<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\UserOption;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface UserOptionRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @param UserOption[] $userOptions
     */
    public function save(array $userOptions): void;

    public function delete(UserOption $userOption): void;

    /**
     * @param Id $userId
     * @return UserOption[]
     */
    public function findByUserId(Id $userId): array;

    public function getReference(Id $id): UserOption;
}
