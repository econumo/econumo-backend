<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Payee\Assembler;

use App\EconumoOneBundle\Application\Payee\Assembler\PayeeToDtoV1ResultAssembler;
use App\EconumoOneBundle\Application\Payee\Dto\OrderPayeeListV1RequestDto;
use App\EconumoOneBundle\Application\Payee\Dto\OrderPayeeListV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\PayeeRepositoryInterface;

class OrderPayeeListV1ResultAssembler
{
    public function __construct(private readonly PayeeRepositoryInterface $payeeRepository, private readonly PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        OrderPayeeListV1RequestDto $dto,
        Id $userId
    ): OrderPayeeListV1ResultDto {
        $result = new OrderPayeeListV1ResultDto();
        $payees = $this->payeeRepository->findAvailableForUserId($userId);
        $result->items = [];
        foreach ($payees as $payee) {
            $result->items[] = $this->payeeToDtoV1ResultAssembler->assemble($payee);
        }

        return $result;
    }
}
