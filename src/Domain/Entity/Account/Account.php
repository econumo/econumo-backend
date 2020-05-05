<?php
declare(strict_types=1);

namespace App\Domain\Entity\Account;

use App\Domain\Entity\Account\ValueObject\AccountType;
use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Doctrine\Repository\AccountRepository")
 * @ORM\Table(name="`account`")
 */
class Account
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
    private $currencyId;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=19, scale=2)
     */
    private $balance;

    /**
     * @var AccountType
     * @ORM\Column(type="account_type")
     */
    private $type;

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
        Id $currencyId,
        float $balance,
        AccountType $type,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->currencyId = $currencyId;
        $this->balance = (string)$balance;
        $this->type = $type;
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

    /**
     * @return string
     */
    public function getBalance(): string
    {
        return $this->balance;
    }

    /**
     * @return AccountType
     */
    public function getType(): AccountType
    {
        return $this->type;
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
