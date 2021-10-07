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
 * @ORM\Entity(repositoryClass="App\Infrastructure\Doctrine\Repository\AccountAccessInviteRepository")
 * @ORM\Table(name="`account_access_invites`")
 */
class AccountAccessInvite
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private Id $accountId;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private Id $recipientId;

    /**
     * @ORM\Column(type="uuid")
     */
    private Id $ownerId;

    /**
     * @ORM\Column(type="account_role")
     */
    private AccountRole $role;

    /**
     * @ORM\Column(type="string", options={"fixed"=true}, length=5)
     */
    private string $code;

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
        Id $recipientId,
        Id $ownerId,
        AccountRole $role,
        string $code,
        \DateTimeInterface $createdAt
    ) {
        $this->accountId = $accountId;
        $this->recipientId = $recipientId;
        $this->ownerId = $ownerId;
        $this->role = $role;
        $this->code = $code;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }
}
