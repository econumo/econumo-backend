<?php

declare(strict_types=1);

namespace App\Infrastructure\AuthToken\UserMapper;

use App\Domain\Entity\Client\Client;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserMapperInterface
{
    /**
     * @param UserInterface $client
     * @return Client
     */
    public function convertToClient(UserInterface $client): Client;

    /**
     * @param Client $client
     * @return UserInterface
     */
    public function convertToUser(Client $client): UserInterface;
}
