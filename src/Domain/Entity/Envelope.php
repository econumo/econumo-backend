<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\EnvelopeName;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\EnvelopeType;
use App\Domain\Events\PlanEnvelopeCreatedEvent;
use App\Domain\Traits\EventTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Envelope
{
    use EventTrait;

    private const DEFAULT_ICON = 'tag';

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    /**
     * @var Collection|Category[]
     */
    private Collection $categories;

    /**
     * @var Collection|Tag[]
     */
    private Collection $tags;

    public function __construct(
        private Id $id,
        private Plan $plan,
        private Currency $currency,
        private ?PlanFolder $folder,
        private EnvelopeType $type,
        private int $position,
        private ?EnvelopeName $name,
        private ?Icon $icon,
        DateTimeInterface $createdAt
    ) {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->registerEvent(new PlanEnvelopeCreatedEvent($id, $plan->getId(), $currency->getId(), ($folder === null ? null : $this->folder->getId()), $type, $position, $name, $icon, $createdAt));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): EnvelopeName
    {
        if ($this->isCategoryConnected()) {
            return $this->getConnectedCategoryName();
        }
        if ($this->isTagConnected()) {
            return $this->getConnectedTagName();
        }

        return $this->name ?? new EnvelopeName('');
    }

    public function getIcon(): Icon
    {
        if ($this->isCategoryConnected()) {
            return $this->getConnectedCategoryIcon();
        }
        if ($this->isTagConnected()) {
            return $this->getConnectedTagIcon();
        }

        return $this->icon ?? new Icon(self::DEFAULT_ICON);
    }

    public function getPlan(): Plan
    {
        return $this->plan;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getFolder(): ?PlanFolder
    {
        return $this->folder;
    }

    public function getType(): EnvelopeType
    {
        return $this->type;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function updateName(EnvelopeName $name): void
    {
        if (!$this->name->isEqual($name)) {
            $this->name = $name;
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
            $envelopeName = $this->isCategoryConnected() ? $this->getConnectedCategoryName() : $this->name;
            $envelopeIcon = $this->isCategoryConnected() ? $this->getConnectedCategoryIcon() : $this->icon;
            $this->categories->add($category);
            $this->name = $this->isCategoryConnected() ? null : $envelopeName;
            $this->icon = $this->isCategoryConnected() ? null : $envelopeIcon;
            $this->updated();
        }
    }

    public function removeCategory(Category $category): void
    {
        if ($this->containsCategory($category)) {
            $envelopeName = $this->isCategoryConnected() ? $this->getConnectedCategoryName() : $this->name;
            $envelopeIcon = $this->isCategoryConnected() ? $this->getConnectedCategoryIcon() : $this->icon;
            $this->categories->removeElement($category);
            $this->name = $this->isCategoryConnected() ? null : $envelopeName;
            $this->icon = $this->isCategoryConnected() ? null : $envelopeIcon;
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

    public function containsTag(Tag $tag): bool
    {
        return $this->tags->contains($tag);
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->containsTag($tag)) {
            $envelopeName = $this->isTagConnected() ? $this->getConnectedTagName() : $this->name;
            $envelopeIcon = $this->isTagConnected() ? $this->getConnectedTagIcon() : $this->icon;
            $this->tags->add($tag);
            $this->name = $this->isTagConnected() ? null : $envelopeName;
            $this->icon = $this->isTagConnected() ? null : $envelopeIcon;
            $this->updated();
        }
    }

    public function removeTag(Tag $tag): void
    {
        if ($this->containsTag($tag)) {
            $envelopeName = $this->isTagConnected() ? $this->getConnectedTagName() : $this->name;
            $envelopeIcon = $this->isTagConnected() ? $this->getConnectedTagIcon() : $this->icon;
            $this->tags->removeElement($tag);
            $this->name = $this->isTagConnected() ? null : $envelopeName;
            $this->icon = $this->isTagConnected() ? null : $envelopeIcon;
            $this->updated();
        }
    }

    /**
     * @return Tag[]|Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function isCategoryConnected(): bool
    {
        if ($this->categories->count() === 1 && $this->tags->count() === 0) {
            $connectedName = $this->getConnectedCategoryName();
            $connectedIcon = $this->getConnectedTagIcon();
            if (($this->name === null || $this->name->isEqual($connectedName)) && ($this->icon === null || $this->icon->isEqual($connectedIcon))) {
                return true;
            }
        }

        return false;
    }

    public function isTagConnected(): bool
    {
        if ($this->categories->count() === 0 && $this->tags->count() === 1) {
            $connectedName = $this->getConnectedTagName();
            $connectedIcon = $this->getConnectedTagIcon();
            if (($this->name === null || $this->name->isEqual($connectedName)) && ($this->icon === null || $this->icon->isEqual($connectedIcon))) {
                return true;
            }
        }

        return false;
    }

    public function isConnected(): bool
    {
        return $this->isCategoryConnected() || $this->isTagConnected();
    }

    public function isArchived(): bool
    {
        if ($this->isCategoryConnected()) {
            return $this->categories->first()->isArchived();
        }

        if ($this->isTagConnected()) {
            return $this->tags->first()->isArchived();
        }

        return false;
    }

    private function getConnectedCategoryName(): EnvelopeName
    {
        return new EnvelopeName($this->categories->first()->getName()->getValue());
    }

    private function getConnectedCategoryIcon(): Icon
    {
        return $this->categories->first()->getIcon();
    }

    private function getConnectedTagName(): EnvelopeName
    {
        return new EnvelopeName($this->tags->first()->getName()->getValue());
    }

    private function getConnectedTagIcon(): Icon
    {
        return new Icon(self::DEFAULT_ICON);
    }
}
