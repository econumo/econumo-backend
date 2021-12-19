<?php

declare(strict_types=1);

namespace App\Domain\Entity;

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
    private string $name;
    private int $position;
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
        string $name,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->name = $name;
        $this->position = 1000;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function containsAccount(Account $account): bool
    {
        return $this->accounts->contains($account);
    }

    public function addAccount(Account $account)
    {
        $this->accounts->add($account);
    }

    public function removeAccount(Account $account)
    {
        $this->accounts->removeElement($account);
    }

    public function belongsTo(Id $userId): bool {
        return $userId->isEqual($this->getUserId());
    }
}
