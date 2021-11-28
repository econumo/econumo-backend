<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Account;
use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\AccountRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository implements AccountRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function getNextIdentity(): Id
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    public function save(Account ...$accounts): void
    {
        try {
            foreach ($accounts as $account) {
                $this->getEntityManager()->persist($account);
            }
            $this->getEntityManager()->flush();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function findByUserId(Id $userId): array
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->leftJoin(AccountAccess::class, 'aa', Join::WITH, 'aa.accountId = a.id')
            ->where('a.user = :user')
            ->orWhere('aa.userId = :id')
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId))
            ->setParameter('id', $userId->getValue())
            ->orderBy('a.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function get(Id $id): Account
    {
        /** @var Account|null $item */
        $item = $this->find($id);
        if ($item === null) {
            throw new NotFoundException(sprintf('Account with ID %s not found', $id));
        }

        return $item;
    }

    public function delete(Id $id): void
    {
        $account = $this->get($id);
        $this->getEntityManager()->remove($account);
        $this->getEntityManager()->flush();
    }

    public function getReference(Id $id): Account
    {
        return $this->getEntityManager()->getReference(Account::class, $id);
    }
}
