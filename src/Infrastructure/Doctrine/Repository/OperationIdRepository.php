<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Infrastructure\Doctrine\Entity\OperationId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method OperationId|null find($id, $lockMode = null, $lockVersion = null)
 * @method OperationId|null findOneBy(array $criteria, array $orderBy = null)
 * @method OperationId[]    findAll()
 * @method OperationId[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationIdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OperationId::class);
    }

    public function get(Id $id): OperationId
    {
        /** @var OperationId|null $item */
        $item = $this->find($id);
        if ($item === null) {
            throw new NotFoundException(sprintf('OperationId %s not found', $id));
        }

        return $item;
    }

    public function save(OperationId ...$items): void
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

    public function remove(OperationId $operationId): void
    {
        $this->getEntityManager()->remove($operationId);
        $this->getEntityManager()->flush();
    }
}
