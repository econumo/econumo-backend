<?php

declare(strict_types=1);


namespace App\Infrastructure\Doctrine\Repository\Traits;

use App\Domain\Entity\ValueObject\Id;

trait GetReferenceTrait
{
    public function getReference(string $entityName, Id $id)
    {
        return $this->getEntityManager()->getReference($entityName, $id);
    }
}
