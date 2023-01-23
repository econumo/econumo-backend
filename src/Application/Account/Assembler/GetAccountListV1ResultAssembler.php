<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\GetAccountListV1RequestDto;
use App\Application\Account\Dto\GetAccountListV1ResultDto;
use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\Id;

class GetAccountListV1ResultAssembler
{
    public function __construct(private readonly AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler)
    {
    }

    /**
     * @param Account[] $accounts
     */
    public function assemble(
        GetAccountListV1RequestDto $dto,
        Id $userId,
        array $accounts
    ): GetAccountListV1ResultDto {
        $result = new GetAccountListV1ResultDto();
        $result->items = [];
        foreach (array_reverse($accounts) as $account) {
            $result->items[] = $this->accountToDtoV1ResultAssembler->assemble($userId, $account);
        }

        return $result;
    }
}
