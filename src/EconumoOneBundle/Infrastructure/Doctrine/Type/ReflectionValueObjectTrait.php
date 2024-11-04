<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Type;

use ReflectionClass;

trait ReflectionValueObjectTrait
{
    /**
     * @param $value
     * @return mixed
     * @throws \ReflectionException
     */
    public function getInstance(string $className, $value)
    {
        $reflection = new ReflectionClass($className);
        $instance = $reflection->newInstanceWithoutConstructor();
        $property = $reflection->getProperty('value');
        $property->setAccessible(true);
        $property->setValue($instance, $value);
        return $instance;
    }
}
