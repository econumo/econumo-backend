<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Events\FolderCreatedEvent;
use App\Domain\Traits\EventTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Folder
{
    use EventTrait;

    private Id $id;

    private FolderName $name;

    private int $position = 1000;

    private bool $isVisible = true;

    private User $user;

    /**
     * @var ArrayCollection|Account[]
     */
    private Collection $accounts;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        Id $id,
        User $user,
        FolderName $name,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->name = $name;
        $this->accounts = new ArrayCollection();
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->registerEvent(new FolderCreatedEvent($user->getId(), $id));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getUserId(): Id
    {
        return $this->user->getId();
    }

    public function getName(): FolderName
    {
        return $this->name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function containsAccount(Account $account): bool
    {
        return $this->accounts->contains($account);
    }

    public function addAccount(Account $account): void
    {
        $this->accounts->add($account);
    }

    public function removeAccount(Account $account): void
    {
        $this->accounts->removeElement($account);
    }

    /**
     * @return Account[]|ArrayCollection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    public function updateName(FolderName $name): void
    {
        if (!$this->name->isEqual($name)) {
            $this->name = $name;
            $this->updated();
        }
    }

    public function updatePosition(int $position): void
    {
        if ($this->position !== $position) {
            $this->position = $position;
            $this->updated();
        }
    }

    public function makeVisible(): void
    {
        if (!$this->isVisible) {
            $this->isVisible = true;
            $this->updated();
        }
    }

    public function makeInvisible(): void
    {
        if ($this->isVisible) {
            $this->isVisible = false;
            $this->updated();
        }
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }
}
