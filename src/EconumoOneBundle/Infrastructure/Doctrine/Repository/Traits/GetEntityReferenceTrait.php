<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

trait GetEntityReferenceTrait
{
    public function getEntityReference(string $entityName, Id $id)
    {
        return $this->getEntityManager()->getReference($entityName, $id);
    }
}
