<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CategoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
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

    public function get(Id $id): Category
    {
        return $this->find($id);
    }
}
