<?php
namespace App\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\UserRepositoryInterface;
use App\Tests\ApiTester;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ramsey\Uuid\Uuid;

class Api extends \Codeception\Module
{
    use ContainerTrait;

    /**
     * @return \App\Domain\Entity\ValueObject\Id
     * @throws \Exception
     */
    public function generateId(): Id
    {
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    public function amAuthenticatedAsJohn(ApiTester $I): void
    {
        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->getContainerService(UserRepositoryInterface::class);
        $user = $userRepository->getByEmail(new Email('john@snow.test'));
        /** @var JWTTokenManagerInterface $tokenManager */
        $tokenManager = $this->getContainerService(JWTTokenManagerInterface::class);
        $token = $tokenManager->create($user);
        $I->amBearerAuthenticated($token);
    }
}
