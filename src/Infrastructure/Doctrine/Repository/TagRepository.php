<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\TagRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

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
        $dql =<<<'DQL'
SELECT u.id FROM App\Domain\Entity\User u
LEFT JOIN App\Domain\Entity\AccountAccess aa WITH aa.userId = :id
LEFT JOIN App\Domain\Entity\Account a WITH a.id = aa.accountId
GROUP BY u.id
DQL;
        $query = $this->getEntityManager()->createQuery($dql)->setParameter('id', $userId->getValue());
        $ids = array_column($query->getScalarResult(), 'id');
        $ids[] = $userId->getValue();
        $ids = array_unique($ids);

        return $this->createQueryBuilder('c')
            ->andWhere('c.userId IN(:ids)')
            ->setParameter('ids', $ids)
            ->orderBy('c.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function get(Id $id): Tag
    {
        /** @var Tag|null $item */
        $item = $this->find($id);
        if ($item === null) {
            throw new NotFoundException(sprintf('Tag with ID %s not found', $id));
        }

        return $item;
    }

    public function save(Tag ...$tags): void
    {
        try {
            $this->getEntityManager()->beginTransaction();
            foreach ($tags as $tag) {
                $this->getEntityManager()->persist($tag);
            }
            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            $this->getEntityManager()->rollback();
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
