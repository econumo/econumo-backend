<?php
declare(strict_types=1);

namespace App\Application\User;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

interface AuthTokenInterface extends JWTTokenManagerInterface
{
}
