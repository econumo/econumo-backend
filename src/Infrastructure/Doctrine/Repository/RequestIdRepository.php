<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\RequestId;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method RequestId|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestId|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestId[]    findAll()
 * @method RequestId[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestIdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestId::class);
    }

    public function get(Id $id): RequestId
    {
        /** @var RequestId|null $item */
        $item = $this->find($id);
        if ($item === null) {
            throw new NotFoundException(sprintf('RequestId %s not found', $id));
        }

        return $item;
    }

    public function save(RequestId ...$items): void
    {
        try {
            foreach ($items as $item) {
                $this->getEntityManager()->persist($item);
            }
            $this->getEntityManager()->flush();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function remove(RequestId $requestId): void
    {
        $this->getEntityManager()->remove($requestId);
        $this->getEntityManager()->flush();
    }
}
