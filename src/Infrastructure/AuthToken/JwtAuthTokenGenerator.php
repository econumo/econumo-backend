<?php

declare(strict_types=1);

namespace App\Infrastructure\AuthToken;

use App\Application\ValueObject\AuthToken;
use App\Domain\Entity\Client\Client;
use App\Infrastructure\AuthToken\UserMapper\UserMapperInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class JwtAuthTokenGenerator implements AuthTokenGeneratorInterface
{
    /**
     * @var UserMapperInterface
     */
    private $userMapper;
    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenGenerator;

    /**
     * JwtAuthTokenGenerator constructor.
     * @param UserMapperInterface $userMapper
     * @param JWTTokenManagerInterface $tokenGenerator
     */
    public function __construct(UserMapperInterface $userMapper, JWTTokenManagerInterface $tokenGenerator)
    {
        $this->userMapper = $userMapper;
        $this->tokenGenerator = $tokenGenerator;
    }


    /**
     * @inheritDoc
     */
    public function generateToken(Client $client): AuthToken
    {
        $user = $this->userMapper->convertToUser($client);

        $token = $this->tokenGenerator->create($user);

        return new AuthToken($token);
    }

}
