<?php

declare(strict_types=1);

namespace App\Application\Payee\Payee;

use App\Application\Payee\Payee\Dto\CreatePayeeV1RequestDto;
use App\Application\Payee\Payee\Dto\CreatePayeeV1ResultDto;
use App\Application\Payee\Payee\Assembler\CreatePayeeV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\PayeeServiceInterface;

class PayeeService
{
    private CreatePayeeV1ResultAssembler $createPayeeV1ResultAssembler;
    private PayeeServiceInterface $payeeService;

    public function __construct(
        CreatePayeeV1ResultAssembler $createPayeeV1ResultAssembler,
        PayeeServiceInterface $payeeService
    ) {
        $this->createPayeeV1ResultAssembler = $createPayeeV1ResultAssembler;
        $this->payeeService = $payeeService;
    }

    public function createPayee(
        CreatePayeeV1RequestDto $dto,
        Id $userId
    ): CreatePayeeV1ResultDto {
        $payee = $this->payeeService->createPayee($userId, new Id($dto->id), $dto->name);
        return $this->createPayeeV1ResultAssembler->assemble($dto, $payee);
    }
}
