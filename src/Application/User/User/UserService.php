<?php

declare(strict_types=1);

namespace App\Application\User\User;

use App\Application\User\User\Dto\LoginUserV1ResultDto;
use App\Application\User\User\Assembler\LoginUserV1ResultAssembler;
use App\Application\User\User\Dto\LogoutUserV1RequestDto;
use App\Application\User\User\Dto\LogoutUserV1ResultDto;
use App\Application\User\User\Assembler\LogoutUserV1ResultAssembler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    private LoginUserV1ResultAssembler $loginUserV1ResultAssembler;
    private LogoutUserV1ResultAssembler $logoutUserV1ResultAssembler;
    private JWTTokenManagerInterface $authToken;

    public function __construct(
        LoginUserV1ResultAssembler $loginUserV1ResultAssembler,
        LogoutUserV1ResultAssembler $logoutUserV1ResultAssembler,
        JWTTokenManagerInterface $authToken
    ) {
        $this->loginUserV1ResultAssembler = $loginUserV1ResultAssembler;
        $this->logoutUserV1ResultAssembler = $logoutUserV1ResultAssembler;
        $this->authToken = $authToken;
    }

    public function loginUser(
        UserInterface $user
    ): LoginUserV1ResultDto {
        // some actions ...
        $token = $this->authToken->create($user);
        return $this->loginUserV1ResultAssembler->assemble($user, $token);
    }

    public function logoutUser(
        string $token
    ): LogoutUserV1ResultDto {
        // some actions ...
        return $this->logoutUserV1ResultAssembler->assemble(new LogoutUserV1RequestDto());
    }
}
