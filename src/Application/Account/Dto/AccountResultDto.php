<?php
declare(strict_types=1);

namespace App\Application\Account\Dto;

use App\Application\Currency\Dto\CurrencyResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "ownerUserId", "name", "position", "currency", "balance", "type", "icon", "sharedAccess"}
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
     * Owner user id
     * @var string
     * @SWG\Property(example="a5e2eee2-56aa-43c6-a827-ca155683ea8d")
     */
    public string $ownerUserId;

    /**
     * @SWG\Property()
     */
    public ?FolderResultDto $folder;

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
