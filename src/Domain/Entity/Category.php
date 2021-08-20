<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\CategoryType;
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
    private Id $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @var string
     */
    private string $name;

    /**
     * @ORM\Column(type="smallint", options={"unsigned"=true, "default"=0})
     * @var int
     */
    private int $position;

    /**
     * @ORM\Column(type="category_type")
     */
    private CategoryType $type;

    /**
     * @var Id
     * @ORM\Column(type="uuid")
     */
    private Id $userId;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

    public function __construct(
        Id $id,
        Id $userId,
        string $name,
        CategoryType $type,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->position = 0;
        $this->type = $type;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getType(): CategoryType
    {
        return $this->type;
    }

    public function getUserId(): Id
    {
        return $this->userId;
    }
}
