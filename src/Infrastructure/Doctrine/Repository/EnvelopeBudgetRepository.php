<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\EnvelopeBudget;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\EnvelopeBudgetRepositoryInterface;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * @method EnvelopeBudget|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnvelopeBudget|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnvelopeBudget[]    findAll()
 * @method EnvelopeBudget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnvelopeBudgetRepository extends ServiceEntityRepository implements EnvelopeBudgetRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnvelopeBudget::class);
    }

    public function getNextIdentity(): Id
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    public function get(Id $id): EnvelopeBudget
    {
        $item = $this->find($id);
        if (!$item instanceof EnvelopeBudget) {
            throw new NotFoundException(sprintf('EnvelopeBudget with ID %s not found', $id));
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

    public function delete(EnvelopeBudget $item): void
    {
        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
    }

    public function getReference(Id $id): EnvelopeBudget
    {
        return $this->getEntityManager()->getReference(EnvelopeBudget::class, $id);
    }

    public function getByEnvelopeIdAndPeriod(Id $envelopeId, DateTimeInterface $period): array
    {
        return $this->findBy(['envelope' => $envelopeId, 'period' => $period]);
    }
}
