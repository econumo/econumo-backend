<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Account\Assembler;

use App\EconumoBundle\Application\Account\Assembler\AccountToDtoV1ResultAssembler;
use App\EconumoBundle\Application\Account\Dto\CreateAccountV1RequestDto;
use App\EconumoBundle\Application\Account\Dto\CreateAccountV1ResultDto;
use App\EconumoBundle\Domain\Entity\Account;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;

class CreateAccountV1ResultAssembler
{
    public function __construct(private readonly AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        CreateAccountV1RequestDto $dto,
        Id $userId,
        Account $account
    ): CreateAccountV1ResultDto {
        $result = new CreateAccountV1ResultDto();
        $result->item = $this->accountToDtoV1ResultAssembler->assemble($userId, $account);

        return $result;
    }
}
