<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;

interface TagFactoryInterface
{
    public function create(Id $userId, Id $tagId, string $name): Tag;
}
