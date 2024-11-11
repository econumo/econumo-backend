<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Repository;

use App\EconumoBundle\Domain\Entity\Currency;
use App\EconumoBundle\Domain\Entity\CurrencyRate;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Exception\NotFoundException;
use App\EconumoBundle\Domain\Repository\CurrencyRateRepositoryInterface;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
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
        if ($date === null) {
            $dateBuilder = $this->createQueryBuilder('cr')
                ->select('cr.publishedAt')
                ->setMaxResults(1)
                ->orderBy('cr.publishedAt', Criteria::DESC);
        } else {
            $dateBuilder = $this->createQueryBuilder('cr')
                ->select('cr.publishedAt')
                ->setMaxResults(1)
                ->orderBy('cr.publishedAt', Criteria::DESC)
                ->where('cr.publishedAt <= :date')
                ->setParameter('date', $date);
        }
        $lastDate = $dateBuilder->getQuery()->getSingleScalarResult();
        $ratesDate = \DateTime::createFromFormat('Y-m-d', $lastDate);

        $query = $this->createQueryBuilder('cr')
            ->andWhere('cr.publishedAt = :date')
            ->setParameter('date', $ratesDate, Types::DATE_MUTABLE)
            ->getQuery();
        $result = $query->getResult();

        return $result;
    }

    public function get(Id $currencyId, DateTimeInterface $date): CurrencyRate
    {
        try {
            $builder = $this->createQueryBuilder('cr');
            $builder->where('cr.currency = :currency')
                ->setParameter('currency', $this->getEntityManager()->getReference(Currency::class, $currencyId))
                ->andWhere('cr.publishedAt = :date')
                ->setParameter('date', $date)
                ->setMaxResults(1);
            $item = $builder->getQuery()->getSingleResult();
            if ($item === null) {
                throw new NotFoundException(sprintf('Currency with identifier (%s, %s) not found', $currencyId->getValue(), $date->format('Y-m-d')));
            }

            return $item;
        } catch (NoResultException) {
            throw new NotFoundException(sprintf('Currency with identifier (%s, %s) not found', $currencyId->getValue(), $date->format('Y-m-d')));
        }
    }

    public function getLatest(Id $currencyId, ?DateTimeInterface $date = null): CurrencyRate
    {
        try {
            $builder = $this->createQueryBuilder('cr');
            $builder->where('cr.currency = :currency')
                ->setParameter('currency', $this->getEntityManager()->getReference(Currency::class, $currencyId))
                ->setMaxResults(1);
            if ($date === null) {
                $builder->orderBy('cr.publishedAt', Criteria::DESC);
            } else {
                $builder->andWhere('cr.publishedAt <= :date')
                    ->setParameter('date', $date);
            }

            $item = $builder->getQuery()->getSingleResult();
            if ($item === null) {
                throw new NotFoundException(sprintf('Currency with identifier %s not found', $item));
            }

            return $item;
        } catch (NoResultException) {
            throw new NotFoundException(sprintf('Currency with identifier %s not found', $currencyId));
        }
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

    public function getNextIdentity(): Id
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    public function getAverage(DateTimeInterface $startDate, DateTimeInterface $endDate, Id $baseCurrencyId): array
    {
        $dateBuilder = $this->createQueryBuilder('cr')
            ->select('IDENTITY(cr.currency) as currencyId, AVG(cr.rate) as rate')
            ->where('cr.publishedAt >= :startDate AND cr.publishedAt < :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->andWhere('cr.baseCurrency = :baseCurrency')
            ->setParameter('baseCurrency', $this->getEntityManager()->getReference(Currency::class, $baseCurrencyId))
            ->groupBy('cr.currency, cr.baseCurrency');
        return $dateBuilder->getQuery()->getArrayResult();
    }
}
