<?php
declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Folder;
use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;

interface FolderFactoryInterface
{
    public function create(Id $userId, FolderName $name): Folder;
}
