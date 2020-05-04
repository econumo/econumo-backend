<?php
declare(strict_types=1);

namespace App\UI\Controller\Api\V1\User\Login;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"username", "password"}
 * )
 */
class Model
{
    /**
     * Имя пользователя
     * @var string
     */
    public $username;

    /**
     * Пароль
     * @var string
     */
    public $password;
}
