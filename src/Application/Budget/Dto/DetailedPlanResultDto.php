<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use App\Application\Category\Dto\CategoryResultDto;
use App\Application\Currency\Dto\CurrencyResultDto;
use App\Application\Tag\Dto\TagResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "ownerUserId", "name", "createdAt", "updatedAt", "currencies", "folders", "envelopes", "categories", "tags", "sharedAccess"}
 * )
 */
class DetailedPlanResultDto
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
     * Plan currencies
     * @var CurrencyResultDto[]
     * @OA\Property()
     */
    public array $currencies = [];

    /**
     * Plan folders
     * @var FolderResultDto[]
     * @OA\Property()
     */
    public array $folders = [];

    /**
     * Plan envelopes
     * @var EnvelopeResultDto[]
     * @OA\Property()
     */
    public array $envelopes = [];

    /**
     * Plan categories
     * @var CategoryResultDto[]
     * @OA\Property()
     */
    public array $categories = [];

    /**
     * Plan tags
     * @var TagResultDto[]
     * @OA\Property()
     */
    public array $tags = [];

    /**
     * Account access
     * @var SharedAccessItemResultDto[]
     * @OA\Property()
     */
    public array $sharedAccess = [];
}
