<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Doctrine\Repository\BudgetDataRepository")
 * @ORM\Table(name="`budget_data`")
 */
class BudgetData
{
    /**
     * @ORM\Id()
     * @ORM\CustomIdGenerator("NONE")
     * @ORM\Column(type="uuid")
     * @var Id
     */
    private $id;

    /**
     * @var Id
     * @ORM\Column(type="uuid")
     */
    private $budgetId;

    /**
     * @ORM\Column(type="date")
     * @var DateTimeInterface
     */
    private $date;

    /**
     * @var Id
     * @ORM\Column(type="uuid")
     */
    private $categoryId;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=19, scale=2)
     */
    private $expectedValue;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=19, scale=2)
     */
    private $actualValue;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct(
        Id $id,
        Id $budgetId,
        DateTimeInterface $date,
        Id $categoryId,
        string $expectedValue,
        string $actualValue,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->budgetId = $budgetId;
        $this->date = $date;
        $this->categoryId = $categoryId;
        $this->expectedValue = $expectedValue;
        $this->actualValue = $actualValue;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    /**
     * @return DateTimeInterface
     */
    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @return Id
     */
    public function getCategoryId(): Id
    {
        return $this->categoryId;
    }

    /**
     * @return string
     */
    public function getExpectedValue(): string
    {
        return $this->expectedValue;
    }

    /**
     * @return string
     */
    public function getActualValue(): string
    {
        return $this->actualValue;
    }
}
