<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service;

use App\EconumoBundle\Domain\Entity\Account;
use App\EconumoBundle\Domain\Entity\Category;
use App\EconumoBundle\Domain\Entity\Payee;
use App\EconumoBundle\Domain\Entity\Tag;
use App\EconumoBundle\Domain\Entity\ValueObject\AccountName;
use App\EconumoBundle\Domain\Entity\ValueObject\AccountType;
use App\EconumoBundle\Domain\Entity\ValueObject\CategoryName;
use App\EconumoBundle\Domain\Entity\ValueObject\CategoryType;
use App\EconumoBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoBundle\Domain\Entity\ValueObject\DecimalNumber;
use App\EconumoBundle\Domain\Entity\ValueObject\FolderName;
use App\EconumoBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Entity\ValueObject\PayeeName;
use App\EconumoBundle\Domain\Entity\ValueObject\TagName;
use App\EconumoBundle\Domain\Entity\ValueObject\TransactionType;
use App\EconumoBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoBundle\Domain\Repository\CategoryRepositoryInterface;
use App\EconumoBundle\Domain\Repository\CurrencyRepositoryInterface;
use App\EconumoBundle\Domain\Repository\FolderRepositoryInterface;
use App\EconumoBundle\Domain\Repository\PayeeRepositoryInterface;
use App\EconumoBundle\Domain\Repository\TagRepositoryInterface;
use App\EconumoBundle\Domain\Service\AccountServiceInterface;
use App\EconumoBundle\Domain\Service\CategoryServiceInterface;
use App\EconumoBundle\Domain\Service\Dto\AccountDto;
use App\EconumoBundle\Domain\Service\Dto\ImportTransactionResultDto;
use App\EconumoBundle\Domain\Service\Dto\TransactionDto;
use App\EconumoBundle\Domain\Service\PayeeServiceInterface;
use App\EconumoBundle\Domain\Service\TagServiceInterface;
use DateTime;
use DateTimeInterface;
use League\Csv\Reader;
use Throwable;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class ImportTransactionService implements ImportTransactionServiceInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private PayeeRepositoryInterface $payeeRepository,
        private TagRepositoryInterface $tagRepository,
        private CurrencyRepositoryInterface $currencyRepository,
        private FolderRepositoryInterface $folderRepository,
        private AccountServiceInterface $accountService,
        private CategoryServiceInterface $categoryService,
        private PayeeServiceInterface $payeeService,
        private TagServiceInterface $tagService,
        private FolderServiceInterface $folderService,
        private TransactionServiceInterface $transactionService,
        private AntiCorruptionServiceInterface $antiCorruptionService,
        private string $baseCurrency
    ) {
    }

    public function importFromCsv(UploadedFile $file, array $mapping, Id $userId): ImportTransactionResultDto
    {
        $result = new ImportTransactionResultDto();

        if (!$file->isValid()) {
            $this->addError($result, 'Invalid file upload');
            return $result;
        }

        if (empty($mapping['account']) || empty($mapping['date'])) {
            $this->addError($result, 'Mapping must include "account" and "date" fields');
            return $result;
        }

        // Validate amount mode
        $useDualAmountMode = !empty($mapping['amountInflow']) || !empty($mapping['amountOutflow']);
        if ($useDualAmountMode && (empty($mapping['amountInflow']) || empty($mapping['amountOutflow']))) {
            $this->addError(
                $result,
                'Mapping must include both "amountInflow" and "amountOutflow" fields when using dual amount mode'
            );
            return $result;
        }

        if (!$useDualAmountMode && empty($mapping['amount'])) {
            $this->addError(
                $result,
                'Mapping must include either "amount" or both "amountInflow" and "amountOutflow"'
            );
            return $result;
        }

        // Load user's accounts, categories, payees, and tags
        $accounts = $this->accountRepository->getAvailableForUserId($userId);
        $categories = $this->categoryRepository->findAvailableForUserId($userId);
        $payees = $this->payeeRepository->findAvailableForUserId($userId);
        $tags = $this->tagRepository->findAvailableForUserId($userId);

        // Parse CSV
        $filePath = $file->getPathname();
        try {
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0);
        } catch (Throwable $throwable) {
            $this->addError($result, 'Failed to open CSV file');
            return $result;
        }

        $header = $csv->getHeader();
        if (empty($header)) {
            $this->addError($result, 'CSV file is empty or invalid');
            return $result;
        }

        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            foreach ($csv->getRecords() as $rowIndex => $rowData) {
                $rowNumber = $rowIndex + 2;

                try {
                    $rowData = $this->normalizeRowKeys($rowData);

                    // Extract fields based on mapping
                    $accountName = $this->getFieldValue($rowData, $mapping['account'] ?? null);
                    $dateStr = $this->getFieldValue($rowData, $mapping['date'] ?? null);

                    if (empty($accountName) || empty($dateStr)) {
                        $this->addError($result, 'Missing required fields (account or date)', $rowNumber);
                        $result->skipped++;
                        continue;
                    }

                    // Find or create account
                    $account = $this->findOrCreateAccount($accounts, $accountName, $userId);

                    // Parse date
                    $date = $this->parseDate($dateStr);
                    if (!$date) {
                        $this->addError($result, "Invalid date format '{$dateStr}'", $rowNumber);
                        $result->skipped++;
                        continue;
                    }

                    // Parse amount
                    if ($useDualAmountMode) {
                        $inflowStr = $this->getFieldValue($rowData, $mapping['amountInflow'] ?? null);
                        $outflowStr = $this->getFieldValue($rowData, $mapping['amountOutflow'] ?? null);

                        $inflow = !empty($inflowStr) ? $this->parseAmount($inflowStr) : null;
                        $outflow = !empty($outflowStr) ? $this->parseAmount($outflowStr) : null;

                        if ($inflow !== null && $outflow !== null) {
                            $this->addError($result, 'Both inflow and outflow specified', $rowNumber);
                            $result->skipped++;
                            continue;
                        }

                        if ($inflow === null && $outflow === null) {
                            $this->addError($result, 'No amount specified', $rowNumber);
                            $result->skipped++;
                            continue;
                        }

                        $amount = $inflow ?? (-1 * $outflow);
                    } else {
                        $amountStr = $this->getFieldValue($rowData, $mapping['amount'] ?? null);
                        if (empty($amountStr)) {
                            $this->addError($result, 'Missing amount', $rowNumber);
                            $result->skipped++;
                            continue;
                        }
                        $amount = $this->parseAmount($amountStr);
                    }

                    if ($amount === null) {
                        $this->addError($result, 'Invalid amount format', $rowNumber);
                        $result->skipped++;
                        continue;
                    }

                    // Parse optional fields
                    $description = $this->getFieldValue($rowData, $mapping['description'] ?? null) ?? '';
                    $categoryName = $this->getFieldValue($rowData, $mapping['category'] ?? null);
                    $payeeName = $this->getFieldValue($rowData, $mapping['payee'] ?? null);
                    $tagName = $this->getFieldValue($rowData, $mapping['tag'] ?? null);

                    // Find or create entities
                    $category = $categoryName ? $this->findOrCreateCategory($categories, $categoryName, $userId, $amount) : null;
                    $payee = $payeeName ? $this->findOrCreatePayee($payees, $payeeName, $userId) : null;
                    $tag = $tagName ? $this->findOrCreateTag($tags, $tagName, $userId) : null;

                    // Create transaction
                    $transactionDto = new TransactionDto();
                    $transactionDto->userId = $userId;
                    $transactionDto->type = new TransactionType($amount >= 0 ? TransactionType::INCOME : TransactionType::EXPENSE);
                    $transactionDto->account = $account;
                    $transactionDto->accountId = $account->getId();
                    $transactionDto->amount = new DecimalNumber((string)abs($amount));
                    $transactionDto->date = $date;
                    $transactionDto->description = $description;
                    $transactionDto->category = $category;
                    $transactionDto->categoryId = $category?->getId();
                    $transactionDto->payee = $payee;
                    $transactionDto->payeeId = $payee?->getId();
                    $transactionDto->tag = $tag;
                    $transactionDto->tagId = $tag?->getId();

                    $this->transactionService->createTransaction($transactionDto);
                    $result->imported++;

                } catch (Throwable $e) {
                    $this->addError($result, $e->getMessage(), $rowNumber);
                    $result->skipped++;
                }
            }

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }
        return $result;
    }

    private function getFieldValue(array $row, ?string $fieldName): ?string
    {
        if ($fieldName === null || !isset($row[$fieldName])) {
            return null;
        }

        $value = trim($row[$fieldName]);
        return $value === '' ? null : $value;
    }

    /**
     * @param Account[] &$accounts
     */
    private function findOrCreateAccount(array &$accounts, string $name, Id $userId): Account
    {
        // Try to find existing account
        foreach ($accounts as $account) {
            if (strcasecmp($account->getName()->getValue(), $name) === 0) {
                return $account;
            }
        }

        // Create new account if not found
        // Use the first existing account's currency, or use base currency if no accounts exist
        if (empty($accounts)) {
            // Use base currency for the first account
            $currency = $this->currencyRepository->getByCode(new CurrencyCode($this->baseCurrency));
            if (!$currency) {
                throw new \RuntimeException("Base currency '{$this->baseCurrency}' not found. Please configure a valid base currency.");
            }
            $currencyId = $currency->getId();
        } else {
            $firstAccount = reset($accounts);
            $currencyId = $firstAccount->getCurrencyId();
        }

        // Get user's folders - if none exist, create a default one
        $folders = $this->folderRepository->getByUserId($userId);
        if (empty($folders)) {
            $folder = $this->folderService->create($userId, new FolderName('Imported Accounts'));
            $folderId = $folder->getId();
        } else {
            $folderId = reset($folders)->getId();
        }

        $accountDto = new AccountDto();
        $accountDto->userId = $userId;
        $accountDto->name = $name;
        $accountDto->currencyId = $currencyId;
        $accountDto->icon = 'wallet';
        $accountDto->balance = new DecimalNumber('0');
        $accountDto->folderId = $folderId;

        $account = $this->accountService->create($accountDto);
        $accounts[] = $account;

        return $account;
    }

    /**
     * @param Category[] &$categories
     */
    private function findOrCreateCategory(array &$categories, string $name, Id $userId, float $amount): Category
    {
        // Try to find existing category
        foreach ($categories as $category) {
            if (strcasecmp($category->getName()->getValue(), $name) === 0) {
                return $category;
            }
        }

        // Create new category if not found
        // Determine type based on transaction amount (income if positive, expense if negative)
        $categoryType = $amount >= 0 ? CategoryType::INCOME : CategoryType::EXPENSE;

        $category = $this->categoryService->createCategory(
            $userId,
            new CategoryName($name),
            new CategoryType($categoryType),
            new Icon('category')
        );

        $categories[] = $category;

        return $category;
    }

    /**
     * @param Payee[] &$payees
     */
    private function findOrCreatePayee(array &$payees, string $name, Id $userId): Payee
    {
        // Try to find existing payee
        foreach ($payees as $payee) {
            if (strcasecmp($payee->getName()->getValue(), $name) === 0) {
                return $payee;
            }
        }

        // Create new payee if not found
        $payee = $this->payeeService->createPayee(
            $userId,
            new PayeeName($name)
        );

        $payees[] = $payee;

        return $payee;
    }

    /**
     * @param Tag[] &$tags
     */
    private function findOrCreateTag(array &$tags, string $name, Id $userId): Tag
    {
        // Try to find existing tag
        foreach ($tags as $tag) {
            if (strcasecmp($tag->getName()->getValue(), $name) === 0) {
                return $tag;
            }
        }

        // Create new tag if not found
        $tag = $this->tagService->createTag(
            $userId,
            new TagName($name)
        );

        $tags[] = $tag;

        return $tag;
    }

    private function parseDate(string $dateStr): ?DateTimeInterface
    {
        // Try common date formats
        $formats = [
            'Y-m-d',
            'd/m/Y',
            'm/d/Y',
            'Y/m/d',
            'd-m-Y',
            'm-d-Y',
            'Y-m-d H:i:s',
            'd/m/Y H:i:s',
            'm/d/Y H:i:s',
        ];

        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $dateStr);
            if ($date !== false) {
                return $date;
            }
        }

        // Try strtotime as fallback
        $timestamp = strtotime($dateStr);
        if ($timestamp !== false) {
            $date = new DateTime();
            $date->setTimestamp($timestamp);
            return $date;
        }

        return null;
    }

    private function parseAmount(string $amountStr): ?float
    {
        $trimmed = trim($amountStr);
        if ($trimmed === '') {
            return null;
        }

        $isNegative = str_starts_with($trimmed, '-') || (str_contains($trimmed, '(') && str_contains($trimmed, ')'));

        // Remove common currency symbols and whitespace, keep separators
        $cleaned = preg_replace('/[^\d.,]/', '', $trimmed);
        if ($cleaned === null || $cleaned === '') {
            return null;
        }

        // Handle different decimal separators
        // If there are multiple commas or dots, assume the last one is decimal separator
        $lastComma = strrpos($cleaned, ',');
        $lastDot = strrpos($cleaned, '.');

        if ($lastComma !== false && $lastDot !== false) {
            // Both present, the later one is decimal separator
            if ($lastComma > $lastDot) {
                $cleaned = str_replace('.', '', $cleaned);
                $cleaned = str_replace(',', '.', $cleaned);
            } else {
                $cleaned = str_replace(',', '', $cleaned);
            }
        } elseif ($lastComma !== false) {
            // Only comma, check if it's decimal separator or thousands
            $commaCount = substr_count($cleaned, ',');
            if ($commaCount === 1 && strlen($cleaned) - $lastComma - 1 <= 2) {
                // Likely decimal separator
                $cleaned = str_replace(',', '.', $cleaned);
            } else {
                // Thousands separator
                $cleaned = str_replace(',', '', $cleaned);
            }
        }

        $cleaned = str_replace(',', '', $cleaned);

        if (!is_numeric($cleaned)) {
            return null;
        }

        $amount = (float)$cleaned;
        if ($isNegative) {
            $amount *= -1;
        }

        return $amount;
    }

    /**
     * @param array<string, string|null> $rowData
     * @return array<string, string|null>
     */
    private function normalizeRowKeys(array $rowData): array
    {
        $normalized = [];
        foreach ($rowData as $key => $value) {
            $normalized[$this->stripUtf8Bom((string)$key)] = $value;
        }

        return $normalized;
    }

    private function stripUtf8Bom(string $value): string
    {
        if (str_starts_with($value, "\xEF\xBB\xBF")) {
            return substr($value, 3);
        }

        return $value;
    }

    private function addError(ImportTransactionResultDto $result, string $message, ?int $rowNumber = null): void
    {
        if (!array_key_exists($message, $result->errors)) {
            $result->errors[$message] = [];
        }

        if ($rowNumber !== null) {
            $result->errors[$message][] = $rowNumber;
        }
    }
}
