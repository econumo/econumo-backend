<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Doctrine\Repository\CategoryRepository")
 * @ORM\Table(name="`categories`")
 */
class Category
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
    private $level;

    /**
     * @ORM\Column(type="smallint", options={"unsigned"=true, "default"=0})
     * @var int
     */
    private $position;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     * @var int
     */
    private $isIncome;

    /**
     * @var Id
     * @ORM\Column(type="uuid")
     */
    private $userId;

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
        string $name,
        int $level,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->level = $level;
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
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function isIncome(): int
    {
        return $this->isIncome;
    }

    /**
     * @return Id
     */
    public function getUserId(): Id
    {
        return $this->userId;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
