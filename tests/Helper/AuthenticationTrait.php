<?php

declare(strict_types=1);


namespace App\Tests\Helper;


use App\Domain\Entity\ValueObject\Email;
use App\Domain\Repository\UserRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

trait AuthenticationTrait
{
    use ContainerTrait;

    public function amAuthenticatedAsJohn(): void
    {
        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->getContainerService(UserRepositoryInterface::class);
        $user = $userRepository->getByEmail(new Email('john@snow.test'));
        /** @var JWTTokenManagerInterface $tokenManager */
        $tokenManager = $this->getContainerService(JWTTokenManagerInterface::class);
        $token = $tokenManager->create($user);
        /** @var \Codeception\Module\REST $rest */
        $rest = $this->getModule('REST');
        $rest->amBearerAuthenticated($token);
    }

}
