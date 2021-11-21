<?php
declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\FolderAccount;
use App\Domain\Entity\ValueObject\Id;

interface FolderAccountFactoryInterface
{
    public function create(Id $folderId, Id $accountId): FolderAccount;
}
