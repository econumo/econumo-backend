<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\Folder;
use App\EconumoOneBundle\Domain\Entity\ValueObject\FolderName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface FolderFactoryInterface
{
    public function create(Id $userId, FolderName $name): Folder;
}
