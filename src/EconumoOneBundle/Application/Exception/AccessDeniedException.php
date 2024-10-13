<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessDeniedException extends AccessDeniedHttpException
{
}
