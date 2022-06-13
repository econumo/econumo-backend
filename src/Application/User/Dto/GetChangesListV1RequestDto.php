<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={}
 * )
 */
class GetChangesListV1RequestDto
{
    /**
     * @SWG\Property(example="2022-01-01 10:00:00")
     */
    public string $foldersUpdatedAt;

    /**
     * @SWG\Property(example="2022-01-01 10:00:00")
     */
    public string $accountsUpdatedAt;

    /**
     * @SWG\Property(example="2022-01-01 10:00:00")
     */
    public string $categoriesUpdatedAt;

    /**
     * @SWG\Property(example="2022-01-01 10:00:00")
     */
    public string $tagsUpdatedAt;

    /**
     * @SWG\Property(example="2022-01-01 10:00:00")
     */
    public string $payeesUpdatedAt;

    /**
     * @SWG\Property(example="2022-01-01 10:00:00")
     */
    public string $currenciesUpdatedAt;

    /**
     * @SWG\Property(example="2022-01-01 10:00:00")
     */
    public string $currencyRatesUpdatedAt;

    /**
     * @SWG\Property(example="2022-01-01 10:00:00")
     */
    public string $transactionsUpdatedAt;

    /**
     * @SWG\Property(example="2022-01-01 10:00:00")
     */
    public string $connectionsUpdatedAt;
}
