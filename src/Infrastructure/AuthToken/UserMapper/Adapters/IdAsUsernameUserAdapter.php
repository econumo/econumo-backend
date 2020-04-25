<?php

declare(strict_types=1);

namespace App\Infrastructure\AuthToken\UserMapper\Adapters;

use App\Domain\Entity\Client\Client;
use Symfony\Component\Security\Core\User\UserInterface;

class IdAsUsernameUserAdapter implements UserInterface
{
    /** @var Client */
    private $client;

    /**
     * IdAsUsernameUserAdapter constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return (string)$this->client->getId();
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        //do nothing
    }

}
