<?php

declare(strict_types=1);

namespace App\Application\Payee\Payee;

use App\Application\Payee\Payee\Dto\CreatePayeeV1RequestDto;
use App\Application\Payee\Payee\Dto\CreatePayeeV1ResultDto;
use App\Application\Payee\Payee\Assembler\CreatePayeeV1ResultAssembler;
use App\Application\RequestIdLockServiceInterface;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\PayeeServiceInterface;

class PayeeService
{
    private CreatePayeeV1ResultAssembler $createPayeeV1ResultAssembler;
    private PayeeServiceInterface $payeeService;
    private AccountAccessServiceInterface $accountAccessService;
    private RequestIdLockServiceInterface $requestIdLockService;

    public function __construct(
        CreatePayeeV1ResultAssembler $createPayeeV1ResultAssembler,
        PayeeServiceInterface $payeeService,
        AccountAccessServiceInterface $accountAccessService,
        RequestIdLockServiceInterface $requestIdLockService
    ) {
        $this->createPayeeV1ResultAssembler = $createPayeeV1ResultAssembler;
        $this->payeeService = $payeeService;
        $this->accountAccessService = $accountAccessService;
        $this->requestIdLockService = $requestIdLockService;
    }

    public function createPayee(
        CreatePayeeV1RequestDto $dto,
        Id $userId
    ): CreatePayeeV1ResultDto {
        $requestId = $this->requestIdLockService->register(new Id($dto->id));
        try {
            if ($dto->accountId !== null) {
                $accountId = new Id($dto->accountId);
                $this->accountAccessService->checkAddPayee($userId, $accountId);
                $payee = $this->payeeService->createPayeeForAccount($userId, $accountId, new Id($dto->id), $dto->name);
            } else {
                $payee = $this->payeeService->createPayee($userId, new Id($dto->id), $dto->name);
            }
            $this->requestIdLockService->update($requestId, $payee->getId());
        } catch (\Throwable $exception) {
            $this->requestIdLockService->remove($requestId);
            throw $exception;
        }

        return $this->createPayeeV1ResultAssembler->assemble($dto, $payee);
    }
}
