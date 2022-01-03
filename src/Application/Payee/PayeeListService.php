<?php

declare(strict_types=1);

namespace App\Application\Payee;

use App\Application\Exception\ValidationException;
use App\Application\Payee\Assembler\GetPayeeListV1ResultAssembler;
use App\Application\Payee\Dto\GetPayeeListV1RequestDto;
use App\Application\Payee\Dto\GetPayeeListV1ResultDto;
use App\Application\Payee\Dto\OrderPayeeListV1RequestDto;
use App\Application\Payee\Dto\OrderPayeeListV1ResultDto;
use App\Application\Payee\Assembler\OrderPayeeListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PayeeRepositoryInterface;
use App\Domain\Service\PayeeServiceInterface;

class PayeeListService
{
    private GetPayeeListV1ResultAssembler $getPayeeListV1ResultAssembler;
    private PayeeRepositoryInterface $payeeRepository;
    private OrderPayeeListV1ResultAssembler $orderPayeeListV1ResultAssembler;
    private PayeeServiceInterface $payeeService;

    public function __construct(
        GetPayeeListV1ResultAssembler $getPayeeListV1ResultAssembler,
        PayeeRepositoryInterface $payeeRepository,
        OrderPayeeListV1ResultAssembler $orderPayeeListV1ResultAssembler,
        PayeeServiceInterface $payeeService
    ) {
        $this->getPayeeListV1ResultAssembler = $getPayeeListV1ResultAssembler;
        $this->payeeRepository = $payeeRepository;
        $this->orderPayeeListV1ResultAssembler = $orderPayeeListV1ResultAssembler;
        $this->payeeService = $payeeService;
    }

    public function getPayeeList(
        GetPayeeListV1RequestDto $dto,
        Id $userId
    ): GetPayeeListV1ResultDto {
        $payees = $this->payeeRepository->findByUserId($userId);
        return $this->getPayeeListV1ResultAssembler->assemble($dto, $payees);
    }

    public function orderPayeeList(
        OrderPayeeListV1RequestDto $dto,
        Id $userId
    ): OrderPayeeListV1ResultDto {
        $payees = $this->payeeRepository->findByUserId($userId);
        $orderedList = [];
        foreach ($dto->ids as $id) {
            $payeeId = new Id($id);
            foreach ($payees as $payee) {
                if ($payee->getId()->isEqual($payeeId)) {
                    $orderedList[] = $payeeId;
                    break;
                }
            }
        }
        if (!count($orderedList)) {
            throw new ValidationException('Payee list is empty');
        }
        $this->payeeService->orderPayees($userId, ...$orderedList);
        return $this->orderPayeeListV1ResultAssembler->assemble($dto, $userId);
    }
}
