<?php

declare(strict_types=1);

namespace App\Infrastructure\AuthToken\UserMapper;

use App\Domain\Entity\Client\Client;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\Client\ClientRepositoryInterface;
use App\Infrastructure\AuthToken\UserMapper\Adapters\IdAsUsernameUserAdapter;
use Symfony\Component\Security\Core\User\UserInterface;

class IdAsUsernameUserMapper implements UserMapperInterface
{
    /** @var ClientRepositoryInterface */
    private $clientRepository;

    /**
     * IdAsUsernameUserMapper constructor.
     * @param ClientRepositoryInterface $clientRepository
     */
    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @inheritDoc
     */
    public function convertToClient(UserInterface $client): Client
    {
        return $this->clientRepository->get(new Id($client->getUsername()));
    }

    /**
     * @inheritDoc
     */
    public function convertToUser(Client $client): UserInterface
    {
        return new IdAsUsernameUserAdapter($client);
    }

}
