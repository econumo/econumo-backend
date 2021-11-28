<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Account;
use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\Category;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\CategoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

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

    public function getNextIdentity(): Id
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    /**
     * @inheritDoc
     */
    public function findByUserId(Id $userId): array
    {
        $dql =<<<'DQL'
SELECT u.id FROM App\Domain\Entity\User u
LEFT JOIN App\Domain\Entity\AccountAccess aa WITH aa.user = :user
LEFT JOIN App\Domain\Entity\Account a WITH a = aa.account
GROUP BY u.id
DQL;
        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId));
        $ids = array_column($query->getScalarResult(), 'id');
        $ids[] = $userId->getValue();
        $users = array_map(function ($id) {
            return $this->getEntityManager()->getReference(User::class, new Id($id));
        }, array_unique($ids));

        $builder = $this->createQueryBuilder('c')
            ->where('c.user IN(:users)')
            ->setParameter('users', $users)
            ->orderBy('c.position', 'ASC');
        return $builder
            ->getQuery()
            ->getResult();
    }

    public function get(Id $id): Category
    {
        /** @var Category|null $item */
        $item = $this->find($id);
        if ($item === null) {
            throw new NotFoundException(sprintf('Category with ID %s not found', $id));
        }

        return $item;
    }

    public function save(Category ...$categories): void
    {
        try {
            foreach ($categories as $category) {
                $this->getEntityManager()->persist($category);
            }
            $this->getEntityManager()->flush();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getReference(Id $id): Category
    {
        return $this->getEntityManager()->getReference(Category::class, $id);
    }
}
