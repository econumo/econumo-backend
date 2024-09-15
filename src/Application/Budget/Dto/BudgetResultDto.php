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
    public string $startedAt;

    /**
     * Excluded accounts
     * @var string[]
     * @OA\Property()
     */
    public array $excludedAccounts = [];

    /**
     * Budget currencies
     * @var string[]
     * @OA\Property()
     */
    public array $currencies = [];

    /**
     * Budget folders
     * @var BudgetFolderResultDto[]
     * @OA\Property()
     */
    public array $folders = [];

    /**
     * Budget envelopes
     * @var BudgetEnvelopeResultDto[]
     * @OA\Property()
     */
    public array $envelopes = [];

    /**
     * Budget categories
     * @var string[]
     * @OA\Property()
     */
    public array $categories = [];

    /**
     * Budget tags
     * @var string[]
     * @OA\Property()
     */
    public array $tags = [];

    /**
     * Budget options
     * @var BudgetEntityOptionResultDto[]
     * @OA\Property()
     */
    public array $entityOptions = [];

    /**
     * Account access
     * @var BudgetAccessResultDto[]
     * @OA\Property()
     */
    public array $sharedAccess = [];
}