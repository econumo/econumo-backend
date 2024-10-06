<?php

declare(strict_types=1);


namespace App\EconumoBundle\Infrastructure\Doctrine\Repository\Traits;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;

trait GetEntityReferenceTrait
{
    public function getEntityReference(string $entityName, Id $id)
    {
        return $this->getEntityManager()->getReference($entityName, $id);
    }
}
