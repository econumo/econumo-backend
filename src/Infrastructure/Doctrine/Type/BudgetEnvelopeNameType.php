<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Entity\ValueObject\BudgetEnvelopeName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class BudgetEnvelopeNameType extends StringType
{
    use ReflectionValueObjectTrait;

    /**
     * @inheritdoc
     * @return BudgetEnvelopeName|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : $this->getInstance(BudgetEnvelopeName::class, $value);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'budget_envelope_name_type';
    }
}
