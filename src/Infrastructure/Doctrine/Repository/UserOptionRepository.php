<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\UserOption;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\UserOptionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * @method UserOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserOption[]    findAll()
 * @method UserOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserOptionRepository extends ServiceEntityRepository implements UserOptionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOption::class);
    }

    public function getNextIdentity(): Id
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    public function save(UserOption ...$userOptions): void
    {
        try {
            foreach ($userOptions as $userOption) {
                $this->getEntityManager()->persist($userOption);
            }
            $this->getEntityManager()->flush();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getReference(Id $id): UserOption
    {
        return $this->getEntityManager()->getReference(UserOption::class, $id);
    }
}
