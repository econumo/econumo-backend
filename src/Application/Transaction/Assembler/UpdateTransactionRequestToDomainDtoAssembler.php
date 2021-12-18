<?php

declare(strict_types=1);

namespace App\Application\Transaction\Assembler;

use App\Application\Transaction\Dto\UpdateTransactionV1RequestDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\TransactionType;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Repository\PayeeRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Service\Dto\TransactionDto;
use DateTime;

class UpdateTransactionRequestToDomainDtoAssembler
{
    private AccountRepositoryInterface $accountRepository;
    private CategoryRepositoryInterface $categoryRepository;
    private TagRepositoryInterface $tagRepository;
    private PayeeRepositoryInterface $payeeRepository;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        CategoryRepositoryInterface $categoryRepository,
        TagRepositoryInterface $tagRepository,
        PayeeRepositoryInterface $payeeRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->payeeRepository = $payeeRepository;
    }

    public function assemble(
        UpdateTransactionV1RequestDto $dto,
        Id $userId
    ): TransactionDto {
        $result = new TransactionDto();
        $result->type = TransactionType::createFromAlias($dto->type);
        $result->userId = $userId;
        $result->amount = $dto->amount;
        $result->accountId = new Id($dto->accountId);
        $result->account = $this->accountRepository->getReference($result->accountId);
        $result->accountRecipientId = null;
        $result->accountRecipient = null;
        $result->amountRecipient = null;
        $result->description = $dto->description === null ? '' : $dto->description;
        $result->date = DateTime::createFromFormat('Y-m-d H:i:s', $dto->date);
        $result->categoryId = null;
        $result->category = null;
        $result->payeeId = null;
        $result->payee = null;
        $result->tagId = null;
        $result->tag = null;

        if ($result->type->isTransfer()) {
            if ($dto->amountRecipient !== null) {
                $result->amountRecipient = $dto->amountRecipient;
            }
            if ($dto->accountRecipientId !== null) {
                $result->accountRecipientId = new Id($dto->accountRecipientId);
                $result->accountRecipient = $this->accountRepository->getReference($result->accountRecipientId);
            }
        } else {
            $result->categoryId = new Id($dto->categoryId);
            $result->category = $this->categoryRepository->getReference($result->categoryId);
            if ($dto->payeeId !== null) {
                $result->payeeId = new Id($dto->payeeId);
                $result->payee = $this->payeeRepository->getReference($result->payeeId);
            }
            if ($dto->tagId !== null) {
                $result->tagId = new Id($dto->tagId);
                $result->tag = $this->tagRepository->getReference($result->tagId);
            }
        }

        return $result;
    }
}
