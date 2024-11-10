<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Account\Assembler;

use App\EconumoOneBundle\Application\Account\Dto\CreateAccountV1RequestDto;
use App\EconumoOneBundle\Application\Account\Dto\CreateAccountV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\Account;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

readonly class CreateAccountV1ResultAssembler
{
    public function __construct(private AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        CreateAccountV1RequestDto $dto,
        Id $userId,
        Account $account,
        float $balance
    ): CreateAccountV1ResultDto {
        $result = new CreateAccountV1ResultDto();
        $result->item = $this->accountToDtoV1ResultAssembler->assemble($userId, $account, $balance);

        return $result;
    }
}
