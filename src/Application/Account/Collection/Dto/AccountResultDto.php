<?php
declare(strict_types=1);

namespace App\Application\Account\Collection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "name", "position", "currencyId", "balance", "type", "icon", "sharedAccess"}
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
     * User owner id
     * @var string
     * @SWG\Property(example="a5e2eee2-56aa-43c6-a827-ca155683ea8d")
     */
    public string $ownerId;

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
     * Currency Id
     * @var string
     * @SWG\Property(example="77adad8a-9982-4e08-8fd7-5ef336c7a5c9")
     */
    public string $currencyId;

    /**
     * Currency alias
     * @var string
     * @SWG\Property(example="RUB")
     */
    public string $currencyAlias;

    /**
     * Currency signature
     * @var string
     * @SWG\Property(example="$")
     */
    public string $currencySign;

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
     * @var AccountRoleResultDto[]
     * @SWG\Property(example="rounded_corner")
     */
    public array $sharedAccess = [];
}
