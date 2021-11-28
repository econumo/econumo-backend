<?php

declare(strict_types=1);


namespace App\Domain\Service;


use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;

interface TagServiceInterface
{
    public function createTag(Id $userId, string $name): Tag;

    public function createTagForAccount(Id $userId, Id $accountId, string $name): Tag;
}
