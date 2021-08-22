<?php

declare(strict_types=1);

namespace App\Application\Transaction\Collection\Assembler;

use App\Application\Transaction\Collection\Dto\TransactionResultDto;
use App\Domain\Entity\Transaction;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Repository\PayeeRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

class TransactionToDtoV1ResultAssembler
{
    private CategoryRepositoryInterface $categoryRepository;
    private TagRepositoryInterface $tagRepository;
    private PayeeRepositoryInterface $payeeRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        TagRepositoryInterface $tagRepository,
        PayeeRepositoryInterface $payeeRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->payeeRepository = $payeeRepository;
        $this->userRepository = $userRepository;
    }

    public function assemble(
        Transaction $transaction
    ): TransactionResultDto {
        $item = new TransactionResultDto();
        $item->id = $transaction->getId()->getValue();
        $item->authorId = $transaction->getUserId()->getValue();
        try {
            $item->authorName = $this->userRepository->get($transaction->getUserId())->getName();
        } catch (\Throwable $e) {
            $item->authorName = '#user_not_found#';
        }
        $item->type = $transaction->getType()->getAlias();
        $item->accountId = $transaction->getAccountId()->getValue();
        $item->accountRecipientId = $transaction->getAccountRecipientId(
        ) === null ? null : $transaction->getAccountRecipientId()->getValue();
        $item->amount = $transaction->getAmount();
        $item->amountRecipient = $transaction->getAmount();
        if ($transaction->getCategoryId() !== null) {
            $item->categoryId = $transaction->getCategoryId()->getValue();
            try {
                $item->categoryName = $this->categoryRepository->get($transaction->getCategoryId())->getName();
            } catch (NotFoundException $e) {
                $item->categoryName = '#error#';
            }
        } else {
            $item->categoryId = null;
            $item->categoryName = '';
        }
        $item->description = $transaction->getDescription();
        $item->payeeId = $transaction->getPayeeId() === null ? null : $transaction->getPayeeId()->getValue();
        if ($item->payeeId) {
            try {
                $item->payeeName = $this->payeeRepository->get($transaction->getPayeeId())->getName();
            } catch (NotFoundException $e) {
                $item->payeeName = '#error#';
            }
        }
        $item->tagId = $transaction->getTagId() === null ? null : $transaction->getTagId()->getValue();
        if ($item->tagId) {
            try {
                $item->tagName = $this->tagRepository->get($transaction->getTagId())->getName();
            } catch (NotFoundException $e) {
                $item->tagName = '#error#';
            }
        }
        $item->date = $transaction->getSpentAt()->format('Y-m-d H:i:s');
        $item->day = $transaction->getSpentAt()->format('Y-m-d');
        $item->time = $transaction->getSpentAt()->format('H:i');
        return $item;
    }
}
