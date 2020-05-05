<?php
declare(strict_types=1);

namespace App\Application\User\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"token", "budgetId"}
 * )
 */
class LoginDisplayDto
{
    /**
     * JWT-токен
     * @var string
     * @SWG\Property(example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c")
     */
    public $token;

    /**
     * Id бюджета по-умолчанию
     * @var string
     * @SWG\Property(example="aba05d76-1928-4d83-97a3-8548c439e91e")
     */
    public $budgetId;
}
