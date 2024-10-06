<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Analytics;

use App\EconumoBundle\Application\Analytics\Dto\GetBalanceV1RequestDto;
use App\EconumoBundle\Application\Analytics\Dto\GetBalanceV1ResultDto;
use App\EconumoBundle\Application\Analytics\Assembler\GetBalanceV1ResultAssembler;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Service\Analytics\BalanceAnalyticsServiceInterface;
use DateTimeImmutable;

class BalanceService
{
    public function __construct(private readonly GetBalanceV1ResultAssembler $getBalanceV1ResultAssembler, private readonly BalanceAnalyticsServiceInterface $balanceAnalyticsService)
    {
    }

    public function getBalance(
        GetBalanceV1RequestDto $dto,
        Id $userId
    ): GetBalanceV1ResultDto {
        $items = $this->balanceAnalyticsService->getBalanceAnalytics(DateTimeImmutable::createFromFormat('Y-m-d', $dto->from), DateTimeImmutable::createFromFormat('Y-m-d', $dto->to), $userId);
        return $this->getBalanceV1ResultAssembler->assemble($dto, $items);
    }
}
