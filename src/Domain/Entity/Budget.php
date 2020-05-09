<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Doctrine\Repository\BudgetRepository")
 * @ORM\Table(name="`budget`")
 */
class Budget
{
    /**
     * @ORM\Id()
     * @ORM\CustomIdGenerator("NONE")
     * @ORM\Column(type="uuid")
     * @var Id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="smallint", options={"unsigned"=true, "default"=0})
     * @var int
     */
    private $position;

    /**
     * @var Id
     * @ORM\Column(type="uuid")
     */
    private $userId;

    /**
     * @var Id
     * @ORM\Column(type="uuid")
     */
    private $currencyId;

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
        Id $userId,
        Id $currencyId,
        string $name,
        int $position,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->currencyId = $currencyId;
        $this->name = $name;
        $this->position = $position;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return Id
     */
    public function getCurrencyId(): Id
    {
        return $this->currencyId;
    }
}
