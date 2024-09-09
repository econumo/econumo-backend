<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\BudgetEnvelopeName;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Events\BudgetEnvelopeCreatedEvent;
use App\Domain\Traits\EntityTrait;
use App\Domain\Traits\EventTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class BudgetEnvelope
{
    use EntityTrait;
    use EventTrait;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    /**
     * @var Collection|Category[]
     */
    private Collection $categories;

    public function __construct(
        private Id $id,
        private Budget $budget,
        private BudgetEnvelopeName $name,
        private Icon $icon,
        DateTimeInterface $createdAt
    ) {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->categories = new ArrayCollection();
        $this->registerEvent(new BudgetEnvelopeCreatedEvent($id, $budget->getId(), $name, $icon, $createdAt));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): BudgetEnvelopeName
    {
        return $this->name;
    }

    public function getIcon(): Icon
    {
        return $this->icon;
    }

    public function getBudget(): Budget
    {
        return $this->budget;
    }

    public function updateName(BudgetEnvelopeName $name): void
    {
        if (!$this->name || !$this->name->isEqual($name)) {
            $this->name = $name;
            $this->updated();
        }
    }

    public function updateIcon(Icon $icon): void
    {
        if (!$this->icon || !$this->icon->isEqual($icon)) {
            $this->icon = $icon;
            $this->updated();
        }
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function containsCategory(Category $category): bool
    {
        return $this->categories->contains($category);
    }

    public function addCategory(Category $category): void
    {
        if (!$this->containsCategory($category)) {
            $this->categories->add($category);
            $this->updated();
        }
    }

    public function removeCategory(Category $category): void
    {
        if ($this->containsCategory($category)) {
            $this->categories->removeElement($category);
            $this->updated();
        }
    }

    /**
     * @return Category[]|Collection
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }
}