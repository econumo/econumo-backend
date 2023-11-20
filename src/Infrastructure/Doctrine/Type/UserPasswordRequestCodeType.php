<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Entity\ValueObject\UserPasswordRequestCode;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class UserPasswordRequestCodeType extends StringType
{
    use ReflectionValueObjectTrait;

    /**
     * @inheritdoc
     * @return UserPasswordRequestCode|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : $this->getInstance(UserPasswordRequestCode::class, $value);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'user_password_request_code_type';
    }
}
