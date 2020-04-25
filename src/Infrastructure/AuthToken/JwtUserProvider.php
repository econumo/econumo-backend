<?php

declare(strict_types=1);

namespace App\Infrastructure\AuthToken;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\Client\ClientRepositoryInterface;
use App\Infrastructure\AuthToken\UserMapper\IdAsUsernameUserMapper;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JwtUserProvider implements UserProviderInterface
{
    /**
     * @var ClientRepositoryInterface
     */
    private $clientRepository;

    /**
     * @var IdAsUsernameUserMapper
     */
    private $mapper;

    public function __construct(ClientRepositoryInterface $clientRepository, IdAsUsernameUserMapper $mapper)
    {
        $this->clientRepository = $clientRepository;
        $this->mapper = $mapper;
    }

    /**
     * @inheritdoc
     */
    public function loadUserByUsername($username)
    {
        return $this->mapper->convertToUser($this->clientRepository->get(new Id($username)));
    }

    /**
     * @inheritdoc
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException(sprintf('Provider "%s" does not support method "%s"', __CLASS__, __FUNCTION__));
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        return false;
    }
}
