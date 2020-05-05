<?php
declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\LoginDisplayDto;
use App\Domain\Entity\ValueObject\Id;

class LoginDisplayAssembler
{
    public function assemble(string $token, Id $budgetId): LoginDisplayDto
    {
        $dto = new LoginDisplayDto();
        $dto->token = $token;
        $dto->budgetId = $budgetId->getValue();

        return $dto;
    }
}
