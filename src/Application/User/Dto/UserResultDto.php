<?php

declare(strict_types=1);


namespace App\Application\User\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "avatar", "name", "email"}
 * )
 */

class UserResultDto
{
    /**
     * User id
     * @SWG\Property(example="f680553f-6b40-407d-a528-5123913be0aa")
     */
    public string $id;

    /**
     * User avatar
     * @var string
     * @SWG\Property(example="https://example.com/avatar.jpg")
     */
    public string $avatar;

    /**
     * User name
     * @SWG\Property(example="John")
     */
    public string $name;

    /**
     * User e-mail
     * @SWG\Property(example="john@snow.test")
     */
    public string $email;
}
