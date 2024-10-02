<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "ownerUserId", "name", "startDate", "createdAt", "updatedAt", "excludedAccounts", "currencies", "folders", "envelopes", "categories", "tags", "sharedAccess"}
 * )
 */
class BudgetResultDto
{
    /**
     * @OA\Property()
     */
    public BudgetMetaResultDto $meta;

    /**
     * @var BudgetCurrencyBalanceResultDto[]
     * @OA\Property()
     */
    public array $balances;

    /**
     * @OA\Property()
     */
    public BudgetStructureResultDto $structure;

//    /**
//     * Excluded accounts
//     * @var string[]
//     * @OA\Property()
//     */
//    public array $excludedAccounts = [];
//
//    /**
//     * Budget currencies
//     * @var string[]
//     * @OA\Property()
//     */
//    public array $currencies = [];
//
//    /**
//     * Budget folders
//     * @var BudgetFolderResultDto[]
//     * @OA\Property()
//     */
//    public array $folders = [];
//
//    /**
//     * Budget envelopes
//     * @var BudgetEnvelopeResultDto[]
//     * @OA\Property()
//     */
//    public array $envelopes = [];
//
//    /**
//     * Budget categories
//     * @var string[]
//     * @OA\Property()
//     */
//    public array $categories = [];
//
//    /**
//     * Budget tags
//     * @var string[]
//     * @OA\Property()
//     */
//    public array $tags = [];
//
//    /**
//     * Budget options
//     * @var BudgetEntityOptionResultDto[]
//     * @OA\Property()
//     */
//    public array $entityOptions = [];
//
//    /**
//     * Account access
//     * @var BudgetSharedAccessResultDto[]
//     * @OA\Property()
//     */
//    public array $sharedAccess = [];
}