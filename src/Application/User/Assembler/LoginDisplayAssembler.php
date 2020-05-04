<?php
declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\LoginDisplayDto;

class LoginDisplayAssembler
{
    public function assemble(string $token): LoginDisplayDto
    {
        $dto = new LoginDisplayDto();
        $dto->token = $token;

        return $dto;
    }
}
