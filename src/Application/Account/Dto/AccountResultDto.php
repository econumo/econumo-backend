<?php
declare(strict_types=1);

namespace App\Application\Account\Dto;

use App\Application\Currency\Dto\CurrencyResultDto;
use App\Application\User\Dto\UserResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "owner", "name", "position", "currency", "balance", "type", "icon", "sharedAccess"}
 * )
 */
class AccountResultDto
{
    /**
     * Id
     * @var string
     * @SWG\Property(example="a5e2eee2-56aa-43c6-a827-ca155683ea8d")
     */
    public string $id;

    /**
     * @SWG\Property()
     */
    public UserResultDto $owner;

    /**
     * Account folder id
     * @SWG\Property(example="1ad16d32-36af-496e-9867-3919436b8d86")
     */
    public ?string $folderId;

    /**
     * Account name
     * @var string
     * @SWG\Property(example="Cash")
     */
    public string $name;

    /**
     * Position
     * @var int
     * @SWG\Property(example="1")
     */
    public int $position;

    /**
     * Currency
     * @SWG\Property()
     */
    public CurrencyResultDto $currency;

    /**
     * Current balance
     * @var float
     * @SWG\Property(example="13.07")
     */
    public float $balance;

    /**
     * Current balance (in default user currency)
     * @var float
     * @SWG\Property(example="13.07")
     */
    public float $balanceUserCurrency;

    /**
     * Account type
     * @var int
     * @SWG\Property(example="1")
     */
    public int $type;

    /**
     * Account icon
     * @var string
     * @SWG\Property(example="rounded_corner")
     */
    public string $icon;

    /**
     * Account access
     * @var SharedAccessItemResultDto[]
     * @SWG\Property()
     */
    public array $sharedAccess = [];
}
