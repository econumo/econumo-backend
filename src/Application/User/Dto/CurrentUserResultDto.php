<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "name", "email", "avatar", "currency", "reportDay"}
 * )
 */
class CurrentUserResultDto
{
    /**
     * User id
     * @OA\Property(example="f680553f-6b40-407d-a528-5123913be0aa")
     */
    public string $id;

    /**
     * User name
     * @var string
     * @OA\Property(example="John")
     */
    public string $name;

    /**
     * User e-mail
     * @var string
     * @OA\Property(example="john@snow.test")
     */
    public string $email;

    /**
     * User avatar
     * @var string
     * @OA\Property(example="https://www.gravatar.com/avatar/f888aa10236977f30255dea605ec99d0")
     */
    public string $avatar;

    /**
     * Default currency
     * @var string
     * @OA\Property(example="USD")
     */
    public string $currency;

    /**
     * Default report day
     * @var int
     * @OA\Property(example=1)
     */
    public int $reportDay;
}
