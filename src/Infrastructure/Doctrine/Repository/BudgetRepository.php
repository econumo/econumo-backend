<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Budget|null find($id, $lockMode = null, $lockVersion = null)
 * @method Budget|null findOneBy(array $criteria, array $orderBy = null)
 * @method Budget[]    findAll()
 * @method Budget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetRepository extends ServiceEntityRepository implements BudgetRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Budget::class);
    }

    /**
     * @inheritDoc
     */
    public function findByUserId(Id $id): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.userId = :id')
            ->setParameter('id', $id->getValue())
            ->orderBy('b.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
