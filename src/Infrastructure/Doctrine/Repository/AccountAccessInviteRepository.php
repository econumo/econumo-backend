<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\AccountAccessInvite;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\AccountAccessInviteRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method AccountAccessInvite|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountAccessInvite|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountAccessInvite[]    findAll()
 * @method AccountAccessInvite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountAccessInviteRepository extends ServiceEntityRepository implements AccountAccessInviteRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountAccessInvite::class);
    }

    public function save(AccountAccessInvite ...$items): void
    {
        try {
            $this->getEntityManager()->beginTransaction();
            foreach ($items as $item) {
                $this->getEntityManager()->persist($item);
            }
            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            $this->getEntityManager()->rollback();
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function get(Id $accountId, Id $recipientId): AccountAccessInvite
    {
        /** @var AccountAccessInvite|null $item */
        $item = $this->findOneBy(['accountId' => $accountId, 'recipientId' => $recipientId]);
        if ($item === null) {
            throw new NotFoundException('AccountAccessInvite not found');
        }

        return $item;
    }

    public function delete(AccountAccessInvite $invite): void
    {
        $this->getEntityManager()->remove($invite);
        $this->getEntityManager()->flush();
    }

    public function getByUserAndCode(Id $userId, string $code): AccountAccessInvite
    {
        /** @var AccountAccessInvite|null $item */
        $item = $this->findOneBy(['recipientId' => $userId, 'code' => $code]);
        if ($item === null) {
            throw new NotFoundException('AccountAccessInvite not found');
        }

        return $item;
    }

    public function getUnacceptedByUser(Id $userId): array
    {
        return $this->findBy(['ownerId' => $userId]);
    }
}
