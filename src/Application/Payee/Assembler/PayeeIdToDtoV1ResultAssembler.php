<?php

declare(strict_types=1);

namespace App\Application\Payee\Assembler;

use App\Application\Payee\Dto\PayeeResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PayeeRepositoryInterface;

class PayeeIdToDtoV1ResultAssembler
{
    private PayeeRepositoryInterface $payeeRepository;

    private PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler;

    public function __construct(
        PayeeRepositoryInterface $payeeRepository,
        PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler
    ) {
        $this->payeeRepository = $payeeRepository;
        $this->payeeToDtoV1ResultAssembler = $payeeToDtoV1ResultAssembler;
    }

    public function assemble(
        Id $payeeId
    ): PayeeResultDto {
        $payee = $this->payeeRepository->get($payeeId);
        return $this->payeeToDtoV1ResultAssembler->assemble($payee);
    }
}
