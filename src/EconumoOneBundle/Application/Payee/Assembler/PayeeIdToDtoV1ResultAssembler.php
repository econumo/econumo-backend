<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Payee\Assembler;

use App\EconumoOneBundle\Application\Payee\Assembler\PayeeToDtoV1ResultAssembler;
use App\EconumoOneBundle\Application\Payee\Dto\PayeeResultDto;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\PayeeRepositoryInterface;

class PayeeIdToDtoV1ResultAssembler
{
    public function __construct(private readonly PayeeRepositoryInterface $payeeRepository, private readonly PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        Id $payeeId
    ): PayeeResultDto {
        $payee = $this->payeeRepository->get($payeeId);
        return $this->payeeToDtoV1ResultAssembler->assemble($payee);
    }
}
