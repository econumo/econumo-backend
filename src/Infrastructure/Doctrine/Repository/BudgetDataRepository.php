<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\BudgetData;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetDataRepositoryInterface;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BudgetData|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudgetData|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudgetData[]    findAll()
 * @method BudgetData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetDataRepository extends ServiceEntityRepository implements BudgetDataRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetData::class);
    }

    /**
     * @inheritDoc
     */
    public function findByBudgetId(Id $budgetId, DateTimeInterface $fromDate, DateTimeInterface $toDate): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.budgetId = :id')
            ->andWhere('b.date > :fromDate AND b.date < :toDate')
            ->setParameters([
                'id' => $budgetId->getValue(),
                'fromDate' => $fromDate,
                'toDate' => $toDate
            ])
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
