<?php

declare(strict_types=1);

namespace App\Application\Payee;

use App\Application\Payee\Dto\CreatePayeeV1RequestDto;
use App\Application\Payee\Dto\CreatePayeeV1ResultDto;
use App\Application\Payee\Assembler\CreatePayeeV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\PayeeServiceInterface;

class PayeeService
{
    private CreatePayeeV1ResultAssembler $createPayeeV1ResultAssembler;
    private PayeeServiceInterface $payeeService;
    private AccountAccessServiceInterface $accountAccessService;

    public function __construct(
        CreatePayeeV1ResultAssembler $createPayeeV1ResultAssembler,
        PayeeServiceInterface $payeeService,
        AccountAccessServiceInterface $accountAccessService
    ) {
        $this->createPayeeV1ResultAssembler = $createPayeeV1ResultAssembler;
        $this->payeeService = $payeeService;
        $this->accountAccessService = $accountAccessService;
    }

    public function createPayee(
        CreatePayeeV1RequestDto $dto,
        Id $userId
    ): CreatePayeeV1ResultDto {
        if ($dto->accountId !== null) {
            $accountId = new Id($dto->accountId);
            $this->accountAccessService->checkAddPayee($userId, $accountId);
            $payee = $this->payeeService->createPayeeForAccount($userId, $accountId, $dto->name);
        } else {
            $payee = $this->payeeService->createPayee($userId, $dto->name);
        }

        return $this->createPayeeV1ResultAssembler->assemble($dto, $payee);
    }
}
