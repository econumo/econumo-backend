<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\UI\Service\Validator;

use App\EconumoOneBundle\Application\Exception\ValidationException;

interface ValidatorInterface
{
    /**
     * @param string $formClass
     * @param array $data
     * @param object|null $model
     * @throws ValidationException
     */
    public function validate(string $formClass, array $data, object $model = null): void;
}
