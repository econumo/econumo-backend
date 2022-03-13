<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\Identifier;
use App\Domain\Events\UserRegisteredEvent;
use App\Domain\Traits\EventTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use EventTrait;

    private Id $id;
    private string $name;

    /**
     * @var string E-mail
     */
    private string $identifier;

    /**
     * @var string The hashed password
     */
    private string $password;

    /**
     * @var string The salt
     */
    private string $salt;

    /**
     * @var ArrayCollection|self[]
     */
    private Collection $connections;

    /**
     * @var ArrayCollection|UserOption[]
     */
    private Collection $options;

    private DateTimeImmutable $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(Id $id, string $salt, string $name, Email $email, DateTimeInterface $createdAt)
    {
        $this->id = $id;
        $this->salt = $salt;
        $this->name = $name;
        $this->identifier = Identifier::createFromEmail($email)->getValue();
        $this->connections = new ArrayCollection();
        $this->options = new ArrayCollection();
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->registerEvent(new UserRegisteredEvent($id));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function updatePassword(string $password): void
    {
        $this->password = $password;
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->identifier;
    }

    public function getAvatarUrl(): string
    {
        return sprintf('https://www.gravatar.com/avatar/%s', md5($this->identifier));
    }

    public function isUserConnected(self $user): bool
    {
        return $this->connections->contains($user);
    }

    public function connectUser(self $user)
    {
        if ($user->getId()->isEqual($this->getId())) {
            return;
        }

        $this->connections->add($user);
    }

    public function deleteConnection(self $user)
    {
        $this->connections->removeElement($user);
    }

    /**
     * @return self[]|ArrayCollection
     */
    public function getConnections()
    {
        return $this->connections;
    }

    public function createOption(UserOption $option)
    {
        foreach ($this->options as $item) {
            if ($item->getName() === $option->getName()) {
                $this->options->removeElement($item);
            }
        }

        $this->options->add($option);
    }

    /**
     * @return UserOption[]|ArrayCollection
     */
    public function getOptions()
    {
        return $this->options;
    }
}
