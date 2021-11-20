<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\LoginUserV1ResultDto;
use Symfony\Component\Security\Core\User\UserInterface;

class LoginUserV1ResultAssembler
{
    public function assemble(
        UserInterface $user,
        string $token
    ): LoginUserV1ResultDto {
        $result = new LoginUserV1ResultDto();
        $result->token = $token;

        return $result;
    }
}
