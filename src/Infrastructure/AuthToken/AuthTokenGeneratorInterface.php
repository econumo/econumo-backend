<?php

declare(strict_types=1);

namespace App\Infrastructure\AuthToken;

use App\Application\ValueObject\AuthToken;
use App\Domain\Entity\Client\Client;

interface AuthTokenGeneratorInterface
{
    /**
     * @param Client $client
     * @return AuthToken
     */
    public function generateToken(Client $client): AuthToken;
}
