<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use App\Application\Currency\Dto\CurrencyResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "ownerUserId", "name", "startDate", "createdAt", "updatedAt", "excludedAccounts", "currencies", "sharedAccess"}
 * )
 */
class BudgetResultDto
{
    /**
     * @OA\Property(example="05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9")
     */
    public string $id;

    /**
     * Owner user id
     * @OA\Property(example="aff21334-96f0-4fb1-84d8-0223d0280954")
     */
    public string $ownerUserId;

    /**
     * @OA\Property(example="Family budget")
     */
    public string $name;

    /**
     * Budget start date
     * @var string
     * @OA\Property(example="2021-01-01 12:15:00")
     */
    public string $startDate;

    /**
     * Created at
     * @var string
     * @OA\Property(example="2021-01-01 12:15:00")
     */
    public string $createdAt;

    /**
     * Updated at
     * @var string
     * @OA\Property(example="2021-01-01 12:15:00")
     */
    public string $updatedAt;

    /**
     * Excluded accounts
     * @var string[]
     * @OA\Property()
     */
    public array $excludedAccounts = [];

    /**
     * Budget currencies
     * @var CurrencyResultDto[]
     * @OA\Property()
     */
    public array $currencies = [];

//    /**
//     * Budget folders
//     * @var PlanFolderResultDto[]
//     * @OA\Property()
//     */
//    public array $folders = [];
//
//    /**
//     * Budget envelopes
//     * @var EnvelopeResultDto[]
//     * @OA\Property()
//     */
//    public array $envelopes = [];
//
//    /**
//     * Budget categories
//     * @var EnvelopeCategoryResultDto[]
//     * @OA\Property()
//     */
//    public array $categories = [];
//
//    /**
//     * Budget tags
//     * @var EnvelopeTagResultDto[]
//     * @OA\Property()
//     */
//    public array $tags = [];

    /**
     * Account access
     * @var BudgetAccessResultDto[]
     * @OA\Property()
     */
    public array $sharedAccess = [];
}