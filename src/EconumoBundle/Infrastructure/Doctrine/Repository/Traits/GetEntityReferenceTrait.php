<?php

declare(strict_types=1);


namespace App\EconumoBundle\Infrastructure\Doctrine\Repository\Traits;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;

trait GetEntityReferenceTrait
{
    /**
     * @param string $entityName
     * @param Id|array $id
     * @return object|string|null
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function getEntityReference(string $entityName, Id | array $id)
    {
        return $this->getEntityManager()->getReference($entityName, $id);
    }
}
