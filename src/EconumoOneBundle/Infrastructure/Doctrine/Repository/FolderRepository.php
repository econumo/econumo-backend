<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Repository;

use App\EconumoOneBundle\Domain\Entity\Folder;
use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\NotFoundException;
use App\EconumoOneBundle\Domain\Repository\FolderRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * @method Folder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Folder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Folder[]    findAll()
 * @method Folder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FolderRepository extends ServiceEntityRepository implements FolderRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Folder::class);
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
    public function getByUserId(Id $userId): array
    {
        return $this->findBy(['user' => $this->getEntityManager()->getReference(User::class, $userId)]);
    }

    public function get(Id $id): Folder
    {
        $item = $this->find($id);
        if (!$item instanceof Folder) {
            throw new NotFoundException(sprintf('Folder with ID %s not found', $id));
        }

        return $item;
    }

    /**
     * @inheritDoc
     */
    public function save(array $items): void
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

    public function delete(Folder $folder): void
    {
        $this->getEntityManager()->remove($folder);
        $this->getEntityManager()->flush();
    }

    public function isUserHasMoreThanOneFolder(Id $userId): bool
    {
        return 1 < count($this->getByUserId($userId));
    }

    public function getLastFolder(Id $userId): Folder
    {
        $builder = $this->createQueryBuilder('f');
        $item = $builder
            ->where('f.user = :user')
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId))
            ->orderBy('f.position', Criteria::DESC)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
        if (!$item instanceof Folder) {
            throw new NotFoundException('Folder not found');
        }

        return $item;
    }
}
