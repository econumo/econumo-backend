<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Entity\ValueObject\TransactionType as ValueObject;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\SmallIntType;

class TransactionType extends SmallIntType
{
    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : new ValueObject((int)$value);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'transaction_type';
    }
}
