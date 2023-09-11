<?php

declare(strict_types=1);

namespace App\Application\Analytics\Assembler;

use App\Application\Analytics\Dto\BalanceResultDto;
use App\Application\Analytics\Dto\GetBalanceV1RequestDto;
use App\Application\Analytics\Dto\GetBalanceV1ResultDto;
use App\Domain\Service\Dto\BalanceAnalyticsDto;

class GetBalanceV1ResultAssembler
{
    /**
     * @param GetBalanceV1RequestDto $dto
     * @param BalanceAnalyticsDto[] $balances
     * @return GetBalanceV1ResultDto
     */
    public function assemble(
        GetBalanceV1RequestDto $dto,
        array $balances
    ): GetBalanceV1ResultDto {
        $result = new GetBalanceV1ResultDto();
        $result->items = [];
        foreach ($balances as $balance) {
            $item = new BalanceResultDto();
            $item->date = $balance->date->format('Y-m-d');
            $item->amount = (string)round($balance->balance, 2);
            $result->items[] = $item;
        }

        return $result;
    }
}
