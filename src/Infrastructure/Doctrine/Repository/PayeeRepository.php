<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Payee;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PayeeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Payee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payee[]    findAll()
 * @method Payee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayeeRepository extends ServiceEntityRepository implements PayeeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payee::class);
    }

    /**
     * @inheritDoc
     */
    public function findByUserId(Id $userId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.userId = :id')
            ->setParameter('id', $userId->getValue())
            ->orderBy('c.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function get(Id $id): Payee
    {
        return $this->find($id);
    }
}
