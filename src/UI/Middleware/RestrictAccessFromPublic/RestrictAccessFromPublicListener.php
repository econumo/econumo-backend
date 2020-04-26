<?php

declare(strict_types=1);

namespace App\UI\Middleware\RestrictAccessFromPublic;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class RestrictAccessFromPublicListener implements EventSubscriberInterface
{

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        $controller = is_array($controller) ? $controller[0] : $controller;

        if (!$controller instanceof AccessibleFromPublicInterface) {
            $isRequestFromPublic = (bool)$event->getRequest()->headers->get('x-pub', 0);

            if ($isRequestFromPublic === true) {
                throw new AccessDeniedHttpException('This method is not accessible from public');
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

}
