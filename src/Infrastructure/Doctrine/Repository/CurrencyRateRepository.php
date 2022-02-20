<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Currency;
use App\Domain\Entity\CurrencyRate;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\CurrencyRateRepositoryInterface;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * @method CurrencyRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyRate[]    findAll()
 * @method CurrencyRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRateRepository extends ServiceEntityRepository implements CurrencyRateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyRate::class);
    }

    /**
     * @inheritDoc
     */
    public function getAll(?DateTimeInterface $date = null): array
    {
        $dateBuilder = $this->createQueryBuilder('cr')
            ->select('cr.publishedAt')
            ->setMaxResults(1);
        if (!$date) {
            $dateBuilder->orderBy('cr.publishedAt', 'DESC');
        } else {
            $dateBuilder->andWhere('cr.publishedAt <= :date')
                ->setParameter('date', $date);
        }
        $lastDate = $dateBuilder->getQuery()->getSingleColumnResult();
        // --- dump ---
        echo '<pre>';
        echo __FILE__ . chr(10);
        echo __METHOD__ . chr(10);
        var_dump($lastDate);
        echo '</pre>';
        exit;
        // --- // ---

        $builder = $this->createQueryBuilder('cr');
        if (!$date) {
            $builder->orderBy('cr.publishedAt', 'DESC');
        } else {
            $builder->andWhere('cr.publishedAt <= :date')
                ->setParameter('date', $date);
        }

        return $builder->getQuery()->getResult();
    }

    public function get(Id $currencyId, ?DateTimeInterface $date = null): CurrencyRate
    {
        try {
            $builder = $this->createQueryBuilder('cr');
            $builder->where('cr.currency = :currency')
                ->setParameter('currency', $this->getEntityManager()->getReference(Currency::class, $currencyId))
                ->setMaxResults(1);
            if (!$date) {
                $builder->orderBy('cr.publishedAt', 'DESC');
            } else {
                $builder->andWhere('cr.publishedAt <= :date')
                    ->setParameter('date', $date);
            }
            $item = $builder->getQuery()->getSingleResult();
            if ($item === null) {
                throw new NotFoundException(sprintf('Currency with identifier %s not found', $item));
            }

            return $item;
        } catch (NoResultException $exception) {
            throw new NotFoundException(sprintf('Currency with identifier %s not found', $currencyId));
        }
    }

    public function save(CurrencyRate ...$items): void
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

    public function getNextIdentity(): Id
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }
}
