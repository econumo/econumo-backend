<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

use App\Application\Account\Dto\AccountResultDto;
use App\Application\Account\Dto\FolderResultDto;
use App\Application\Category\Dto\CategoryResultDto;
use App\Application\Connection\Dto\ConnectionResultDto;
use App\Application\Currency\Dto\CurrencyRateResultDto;
use App\Application\Currency\Dto\CurrencyResultDto;
use App\Application\Payee\Dto\PayeeResultDto;
use App\Application\Tag\Dto\TagResultDto;
use App\Application\Transaction\Dto\TransactionResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"accounts", "folders", "categories", "tags", "payees", "currencies", "currencyRates", "transactions", "connections"}
 * )
 */
class GetChangesListV1ResultDto
{
    /**
     * @var AccountResultDto[]
     * @SWG\Property()
     */
    public array $accounts = [];

    /**
     * @var FolderResultDto[]
     * @SWG\Property()
     */
    public array $folders = [];

    /**
     * @var CategoryResultDto[]
     * @SWG\Property()
     */
    public array $categories = [];

    /**
     * @var TagResultDto[]
     * @SWG\Property()
     */
    public array $tags = [];

    /**
     * @var PayeeResultDto[]
     * @SWG\Property()
     */
    public array $payees = [];

    /**
     * @var CurrencyResultDto[]
     * @SWG\Property()
     */
    public array $currencies = [];

    /**
     * @var CurrencyRateResultDto[]
     * @SWG\Property()
     */
    public array $currencyRates = [];

    /**
     * @var TransactionResultDto[]
     * @SWG\Property()
     */
    public array $transactions = [];

    /**
     * @var ConnectionResultDto[]
     * @SWG\Property()
     */
    public array $connections = [];
}
