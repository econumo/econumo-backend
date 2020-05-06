<?php
declare(strict_types=1);

namespace App\Application\User;

use App\Application\User\Assembler\LoginDisplayAssembler;
use App\Application\User\Dto\LoginDisplayDto;
use App\Application\User\Dto\LogoutDisplayDto;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserService
{
    /**
     * @var JWTTokenManagerInterface
     */
    private $authToken;
    /**
     * @var LoginDisplayAssembler
     */
    private $loginDisplayAssembler;

    public function __construct(
        JWTTokenManagerInterface $authToken,
        LoginDisplayAssembler $loginDisplayAssembler
    ) {
        $this->authToken = $authToken;
        $this->loginDisplayAssembler = $loginDisplayAssembler;
    }

    public function login(User $user): LoginDisplayDto
    {
        $token = $this->authToken->create($user);
        // @todo: выборка бюджета
        $budgetId = new Id('budget_1');

        return $this->loginDisplayAssembler->assemble($token, $budgetId);
    }

    public function logout(string $token): LogoutDisplayDto
    {
        // @todo: инвалидировать токен
        return new LogoutDisplayDto();
    }
}
