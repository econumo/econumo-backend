<?php

declare(strict_types=1);

namespace App\Application\User\Updates\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"profileUpdated", "accountsUpdated", "categoriesUpdated", "payeesUpdated", "tagsUpdated", "transactionsUpdated"}
 * )
 */
class CheckUpdatesV1ResultDto
{
    /**
     * @SWG\Property(example="1")
     */
    public int $profileUpdated;

    /**
     * @SWG\Property(example="1")
     */
    public int $accountsUpdated;

    /**
     * @SWG\Property(example="1")
     */
    public int $categoriesUpdated;

    /**
     * @SWG\Property(example="1")
     */
    public int $payeesUpdated;

    /**
     * @SWG\Property(example="1")
     */
    public int $tagsUpdated;

    /**
     * @SWG\Property(example="1")
     */
    public int $transactionsUpdated;
}
