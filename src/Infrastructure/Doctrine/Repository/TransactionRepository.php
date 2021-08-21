<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TransactionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository implements TransactionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * @inheritDoc
     */
    public function findByAccountId(Id $id): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.accountId = :id')
            ->orWhere('c.accountRecipientId = :id')
            ->setParameter('id', $id->getValue())
            ->orderBy('c.spentAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
