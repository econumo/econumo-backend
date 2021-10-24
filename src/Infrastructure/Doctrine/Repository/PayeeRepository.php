<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Payee;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\PayeeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method Payee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payee[]    findAll()
 * @method Payee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayeeRepository extends ServiceEntityRepository implements PayeeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payee::class);
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

    public function get(Id $id): Payee
    {
        /** @var Payee|null $item */
        $item = $this->find($id);
        if ($item === null) {
            throw new NotFoundException(sprintf('Payee with ID %s not found', $id));
        }

        return $item;
    }

    public function save(Payee ...$payees): void
    {
        try {
            foreach ($payees as $payee) {
                $this->getEntityManager()->persist($payee);
            }
            $this->getEntityManager()->flush();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
