<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TagRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository implements TagRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
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

    public function get(Id $id): Tag
    {
        return $this->find($id);
    }
}
