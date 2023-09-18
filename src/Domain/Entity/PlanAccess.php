<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\UserRole;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class PlanAccess
{
    private Id $id;

    private bool $isAccepted = false;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Plan $plan,
        private User $user,
        private UserRole $role,
        \DateTimeInterface $createdAt
    ) {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getPlan(): Plan
    {
        return $this->plan;
    }

    public function getPlanId(): Id
    {
        return $this->plan->getId();
    }

    public function getUserId(): Id
    {
        return $this->user->getId();
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function updateRole(UserRole $role): void
    {
        if (!$this->role->isEqual($role)) {
            $this->role = $role;
            $this->updated();
        }
    }

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function isAccepted(): bool
    {
        return $this->isAccepted;
    }

    public function accept(bool $isAccepted): void
    {
        if ($this->isAccepted !== $isAccepted) {
            $this->isAccepted = $isAccepted;
            $this->updated();
        }
    }
}
