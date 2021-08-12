<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\Identifier;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Doctrine\Repository\UserRepository")
 * @ORM\Table(name="`users`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\CustomIdGenerator("NONE")
     * @ORM\Column(type="uuid")
     * @var Id
     */
    private Id $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @var string The hashed e-mail
     * @ORM\Column(type="string", unique=true, length=40)
     */
    private string $identifier;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @var string The salt
     * @ORM\Column(type="string", length=40)
     */
    private string $salt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

    public function __construct(Id $id, string $salt, string $name, Email $email, DateTimeInterface $createdAt)
    {
        $this->id = $id;
        $this->salt = $salt;
        $this->name = $name;
        $this->identifier = Identifier::createFromEmail($email)->getValue();
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
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
}
