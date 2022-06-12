<?php

declare(strict_types=1);

namespace App\Application\Payee;

use App\Application\Exception\AccessDeniedException;
use App\Application\Exception\ValidationException;
use App\Application\Payee\Assembler\ArchivePayeeV1ResultAssembler;
use App\Application\Payee\Assembler\CreatePayeeV1ResultAssembler;
use App\Application\Payee\Assembler\DeletePayeeV1ResultAssembler;
use App\Application\Payee\Assembler\UpdatePayeeV1ResultAssembler;
use App\Application\Payee\Dto\ArchivePayeeV1RequestDto;
use App\Application\Payee\Dto\ArchivePayeeV1ResultDto;
use App\Application\Payee\Dto\CreatePayeeV1RequestDto;
use App\Application\Payee\Dto\CreatePayeeV1ResultDto;
use App\Application\Payee\Dto\DeletePayeeV1RequestDto;
use App\Application\Payee\Dto\DeletePayeeV1ResultDto;
use App\Application\Payee\Dto\UnarchivePayeeV1RequestDto;
use App\Application\Payee\Dto\UnarchivePayeeV1ResultDto;
use App\Application\Payee\Assembler\UnarchivePayeeV1ResultAssembler;
use App\Application\Payee\Dto\UpdatePayeeV1RequestDto;
use App\Application\Payee\Dto\UpdatePayeeV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PayeeName;
use App\Domain\Exception\PayeeAlreadyExistsException;
use App\Domain\Repository\PayeeRepositoryInterface;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\PayeeServiceInterface;
use App\Domain\Service\Translation\TranslationServiceInterface;

class PayeeService
{
    private CreatePayeeV1ResultAssembler $createPayeeV1ResultAssembler;
    private PayeeServiceInterface $payeeService;
    private AccountAccessServiceInterface $accountAccessService;
    private UpdatePayeeV1ResultAssembler $updatePayeeV1ResultAssembler;
    private PayeeRepositoryInterface $payeeRepository;
    private DeletePayeeV1ResultAssembler $deletePayeeV1ResultAssembler;
    private ArchivePayeeV1ResultAssembler $archivePayeeV1ResultAssembler;
    private UnarchivePayeeV1ResultAssembler $unarchivePayeeV1ResultAssembler;
    private TranslationServiceInterface $translationService;

    public function __construct(
        CreatePayeeV1ResultAssembler $createPayeeV1ResultAssembler,
        PayeeServiceInterface $payeeService,
        AccountAccessServiceInterface $accountAccessService,
        UpdatePayeeV1ResultAssembler $updatePayeeV1ResultAssembler,
        PayeeRepositoryInterface $payeeRepository,
        DeletePayeeV1ResultAssembler $deletePayeeV1ResultAssembler,
        ArchivePayeeV1ResultAssembler $archivePayeeV1ResultAssembler,
        UnarchivePayeeV1ResultAssembler $unarchivePayeeV1ResultAssembler,
        TranslationServiceInterface $translationService
    ) {
        $this->createPayeeV1ResultAssembler = $createPayeeV1ResultAssembler;
        $this->payeeService = $payeeService;
        $this->accountAccessService = $accountAccessService;
        $this->updatePayeeV1ResultAssembler = $updatePayeeV1ResultAssembler;
        $this->payeeRepository = $payeeRepository;
        $this->deletePayeeV1ResultAssembler = $deletePayeeV1ResultAssembler;
        $this->archivePayeeV1ResultAssembler = $archivePayeeV1ResultAssembler;
        $this->unarchivePayeeV1ResultAssembler = $unarchivePayeeV1ResultAssembler;
        $this->translationService = $translationService;
    }

    public function createPayee(
        CreatePayeeV1RequestDto $dto,
        Id $userId
    ): CreatePayeeV1ResultDto {
        if ($dto->accountId !== null) {
            $accountId = new Id($dto->accountId);
            $this->accountAccessService->checkAddPayee($userId, $accountId);
            $payee = $this->payeeService->createPayeeForAccount($userId, $accountId, new PayeeName($dto->name));
        } else {
            $payee = $this->payeeService->createPayee($userId, new PayeeName($dto->name));
        }

        return $this->createPayeeV1ResultAssembler->assemble($dto, $payee);
    }

    public function updatePayee(
        UpdatePayeeV1RequestDto $dto,
        Id $userId
    ): UpdatePayeeV1ResultDto {
        $payeeId = new Id($dto->id);
        $tag = $this->payeeRepository->get($payeeId);
        if (!$tag->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }
        try {
            $this->payeeService->updatePayee($payeeId, new PayeeName($dto->name));
        } catch (PayeeAlreadyExistsException $exception) {
            throw new ValidationException($this->translationService->trans('payee.payee.already_exists', ['name' => $dto->name]));
        }
        return $this->updatePayeeV1ResultAssembler->assemble($dto);
    }

    public function deletePayee(
        DeletePayeeV1RequestDto $dto,
        Id $userId
    ): DeletePayeeV1ResultDto {
        $payeeId = new Id($dto->id);
        $payee = $this->payeeRepository->get($payeeId);
        if (!$payee->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }
        $this->payeeService->deletePayee($payeeId);
        return $this->deletePayeeV1ResultAssembler->assemble($dto);
    }

    public function archivePayee(
        ArchivePayeeV1RequestDto $dto,
        Id $userId
    ): ArchivePayeeV1ResultDto {
        $payeeId = new Id($dto->id);
        $payee = $this->payeeRepository->get($payeeId);
        if (!$payee->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }
        $this->payeeService->archivePayee($payeeId);
        return $this->archivePayeeV1ResultAssembler->assemble($dto);
    }

    public function unarchivePayee(
        UnarchivePayeeV1RequestDto $dto,
        Id $userId
    ): UnarchivePayeeV1ResultDto {
        $payeeId = new Id($dto->id);
        $payee = $this->payeeRepository->get($payeeId);
        if (!$payee->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }
        $this->payeeService->unarchivePayee($payeeId);
        return $this->unarchivePayeeV1ResultAssembler->assemble($dto);
    }
}
