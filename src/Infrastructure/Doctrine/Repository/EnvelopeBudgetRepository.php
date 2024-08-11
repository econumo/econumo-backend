<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Envelope;
use App\Domain\Entity\EnvelopeBudget;
use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\EnvelopeBudgetRepositoryInterface;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * @method EnvelopeBudget|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnvelopeBudget|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnvelopeBudget[]    findAll()
 * @method EnvelopeBudget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnvelopeBudgetRepository extends ServiceEntityRepository implements EnvelopeBudgetRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnvelopeBudget::class);
    }

    public function getNextIdentity(): Id
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    public function get(Id $id): EnvelopeBudget
    {
        $item = $this->find($id);
        if (!$item instanceof EnvelopeBudget) {
            throw new NotFoundException(sprintf('EnvelopeBudget with ID %s not found', $id));
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
        } catch (ORMException|ORMInvalidArgumentException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function delete(EnvelopeBudget $item): void
    {
        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
    }

    public function getReference(Id $id): EnvelopeBudget
    {
        return $this->getEntityManager()->getReference(EnvelopeBudget::class, $id);
    }

    public function getByEnvelopeIdAndPeriod(Id $envelopeId, DateTimeInterface $period): EnvelopeBudget
    {
        $item = $this->findOneBy([
            'envelope' => $this->getEntityManager()->getReference(Envelope::class, $envelopeId),
            'period' => \DateTimeImmutable::createFromInterface($period)
        ]);
        if (!$item instanceof EnvelopeBudget) {
            throw new NotFoundException(sprintf('EnvelopeBudget with ID %s not found', $envelopeId));
        }

        return $item;
    }

    public function getByPlanIdAndPeriod(Id $planId, DateTimeInterface $period): array
    {
        $dateBuilder = $this->createQueryBuilder('eb')
            ->select('eb')
            ->leftJoin('eb.envelope', 'e')
            ->where('eb.period = :period')
            ->setParameter('period', $period)
            ->andWhere('e.plan = :plan')
            ->setParameter('plan', $this->getEntityManager()->getReference(Plan::class, $planId));

        return $dateBuilder->getQuery()->getResult();
    }

    public function getSumByPlanIdAndPeriod(Id $planId, DateTimeInterface $period): array
    {
        $planIdString = $planId->getValue();
        $periodString = $period->format('Y-m-d H:i:s');
        $sql = <<<SQL
SELECT e.id as envelope_id, COALESCE(SUM(eb.amount), 0) as budget FROM envelopes e
LEFT JOIN plans p ON e.plan_id = p.id
LEFT JOIN envelope_budgets eb ON e.id = eb.envelope_id AND eb.period >= p.start_date AND eb.period < '{$periodString}'
WHERE e.plan_id = '{$planIdString}'
GROUP BY e.id;
SQL;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('envelope_id', 'envelope_id');
        $rsm->addScalarResult('budget', 'budget', 'float');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $result = $query->getResult();
        return array_column($result, null, 'envelope_id');
    }

    public function deleteByPlanId(Id $planId): void
    {
        $planIdString = $planId->getValue();
        $sql = <<<SQL
DELETE FROM envelope_budgets 
WHERE envelope_id IN (
    SELECT e.id 
    FROM envelopes e 
    WHERE e.plan_id = "{$planIdString}"
);
SQL;
        $rsm = new ResultSetMapping();
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->execute();
    }
}
