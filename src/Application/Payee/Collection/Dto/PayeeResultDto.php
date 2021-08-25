<?php
declare(strict_types=1);

namespace App\Application\Payee\Collection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "name", "position", "ownerId"}
 * )
 */
class PayeeResultDto
{
    /**
     * Id
     * @var string
     * @SWG\Property(example="1b8559ac-4c77-47e4-a95c-1530a5274ab7")
     */
    public string $id;

    /**
     * Owner id
     * @SWG\Property(example="f680553f-6b40-407d-a528-5123913be0aa")
     */
    public string $ownerId;

    /**
     * Name
     * @var string
     * @SWG\Property(example="Apple")
     */
    public string $name;

    /**
     * Position
     * @var int
     * @SWG\Property(example="0")
     */
    public int $position;
}