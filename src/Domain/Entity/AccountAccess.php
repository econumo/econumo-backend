<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\AccountRole;
use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Doctrine\Repository\AccountAccessRepository")
 * @ORM\Table(name="`account_access`")
 */
class AccountAccess
{
    /**
     * @ORM\Id()
     * @ORM\CustomIdGenerator("NONE")
     * @ORM\Column(type="uuid")
     */
    private Id $accountId;

    /**
     * @ORM\Id()
     * @ORM\CustomIdGenerator("NONE")
     * @ORM\Column(type="uuid")
     */
    private Id $userId;

    /**
     * @ORM\Column(type="account_role")
     */
    private AccountRole $role;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

    public function __construct(
        Id $accountId,
        Id $userId,
        AccountRole $role,
        \DateTimeInterface $createdAt
    ) {
        $this->accountId = $accountId;
        $this->userId = $userId;
        $this->role = $role;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getAccountId(): Id
    {
        return $this->accountId;
    }

    public function getUserId(): Id
    {
        return $this->userId;
    }

    public function getRole(): AccountRole
    {
        return $this->role;
    }
}
