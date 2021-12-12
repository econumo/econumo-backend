<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\PasswordUserRequest;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\PasswordUserRequestRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * @method PasswordUserRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordUserRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordUserRequest[]    findAll()
 * @method PasswordUserRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordUserRequestRepository extends ServiceEntityRepository implements PasswordUserRequestRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordUserRequest::class);
    }

    public function getNextIdentity(): Id
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    public function save(PasswordUserRequest ...$items): void
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

    public function getByCode(string $code): PasswordUserRequest
    {
        /** @var PasswordUserRequest|null $item */
        $item = $this->findOneBy(['code' => $code]);
        if ($item === null) {
            throw new NotFoundException(sprintf('PasswordUserRequest with ID %s not found', $code));
        }

        return $item;
    }
}
