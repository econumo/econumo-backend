<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Entity;

use App\EconumoOneBundle\Domain\Entity\Account;
use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Traits\EntityTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class AccountOptions
{
    use EntityTrait;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Account $account,
        private User $user,
        private int $position,
        DateTimeInterface $createdAt
    ) {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getAccountId(): Id
    {
        return $this->account->getId();
    }

    public function getUserId(): Id
    {
        return $this->user->getId();
    }

    public function updatePosition(int $position): void
    {
        if ($this->position !== $position) {
            $this->position = $position;
            $this->updated();
        }
    }

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }
}
