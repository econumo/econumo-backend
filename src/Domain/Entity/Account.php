<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\AccountType;
use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Doctrine\Repository\AccountRepository")
 * @ORM\Table(name="`accounts`")
 */
class Account
{
    /**
     * @ORM\Id()
     * @ORM\CustomIdGenerator("NONE")
     * @ORM\Column(type="uuid")
     */
    private Id $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $name;

    /**
     * @ORM\Column(type="smallint", options={"unsigned"=true, "default"=0})
     */
    private int $position;

    /**
     * @ORM\Column(type="uuid")
     */
    private Id $currencyId;

    /**
     * @ORM\Column(type="decimal", precision=19, scale=2)
     */
    private string $balance;

    /**
     * @ORM\Column(type="account_type")
     */
    private AccountType $type;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $icon;

    /**
     * @ORM\Column(type="uuid")
     */
    private Id $userId;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

    public function __construct(
        Id $id,
        Id $userId,
        string $name,
        Id $currencyId,
        float $balance,
        AccountType $type,
        string $icon,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->currencyId = $currencyId;
        $this->balance = (string)$balance;
        $this->type = $type;
        $this->icon = $icon;
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

    public function getCurrencyId(): Id
    {
        return $this->currencyId;
    }

    public function getBalance(): float
    {
        return (float)$this->balance;
    }

    public function getType(): AccountType
    {
        return $this->type;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }
}
