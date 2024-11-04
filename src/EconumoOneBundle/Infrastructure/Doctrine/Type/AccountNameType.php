<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Type;

use App\EconumoOneBundle\Domain\Entity\ValueObject\AccountName;
use App\EconumoOneBundle\Infrastructure\Doctrine\Type\ReflectionValueObjectTrait;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class AccountNameType extends StringType
{
    use ReflectionValueObjectTrait;

    /**
     * @inheritdoc
     * @return AccountName|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : $this->getInstance(AccountName::class, $value);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'account_name_type';
    }
}
