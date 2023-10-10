<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Envelope;
use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\EnvelopeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * @method Envelope|null find($id, $lockMode = null, $lockVersion = null)
 * @method Envelope|null findOneBy(array $criteria, array $orderBy = null)
 * @method Envelope[]    findAll()
 * @method Envelope[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnvelopeRepository extends ServiceEntityRepository implements EnvelopeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Envelope::class);
    }

    public function getNextIdentity(): Id
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    /**
     * @inheritDoc
     */
    public function getByPlanId(Id $planId): array
    {
        return $this->findBy(['plan' => $this->getEntityManager()->getReference(Plan::class, $planId)]);
    }

    public function get(Id $id): Envelope
    {
        $item = $this->find($id);
        if (!$item instanceof Envelope) {
            throw new NotFoundException(sprintf('Envelope with ID %s not found', $id));
        }

        return $item;
    }

    /**
     * @inheritDoc
     */
    public function save(array $items): void
    {
        try {
            foreach ($items as $item) {
                $this->getEntityManager()->persist($item);
            }

            $this->getEntityManager()->flush();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function delete(Envelope $item): void
    {
        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
    }

    public function getReference(Id $id): Envelope
    {
        return $this->getEntityManager()->getReference(Envelope::class, $id);
    }
}
