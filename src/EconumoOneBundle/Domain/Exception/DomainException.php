<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Exception;

use App\EconumoOneBundle\Domain\Exception\DomainExceptionInterface;
use DomainException as BaseDomainException;

class DomainException extends BaseDomainException implements DomainExceptionInterface
{

}
