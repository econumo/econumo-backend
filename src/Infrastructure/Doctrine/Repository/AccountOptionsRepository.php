<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Account;
use App\Domain\Entity\AccountOptions;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\AccountOptionsRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method AccountOptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountOptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountOptions[]    findAll()
 * @method AccountOptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountOptionsRepository extends ServiceEntityRepository implements AccountOptionsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountOptions::class);
    }

    public function getByUserId(Id $userId): array
    {
        return $this->findBy(['user' => $this->getEntityManager()->getReference(User::class, $userId)]);
    }

    public function save(AccountOptions ...$accountOptions): void
    {
        try {
            foreach ($accountOptions as $position) {
                $this->getEntityManager()->persist($position);
            }
            $this->getEntityManager()->flush();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function get(Id $accountId, Id $userId): AccountOptions
    {
        /** @var AccountOptions|null $item */
        $item = $this->findOneBy([
            'account' => $this->getEntityManager()->getReference(Account::class, $accountId),
            'user' => $this->getEntityManager()->getReference(User::class, $userId)
        ]);
        if ($item === null) {
            throw new NotFoundException(sprintf('AccountOptions for account_id %s user_id %s not found', $accountId, $userId));
        }

        return $item;
    }
}
