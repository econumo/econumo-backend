<?php

declare(strict_types=1);

namespace App\UI\Middleware\AcceptUserLanguage;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $acceptLanguage = $request->headers->get('accept-language');
        if (empty($acceptLanguage)) {
            return;
        }
        $parts = HeaderUtils::split($acceptLanguage, ',;');
        if (empty($parts[0][0])) {
            return;
        }

        // Symfony expects underscore instead of dash in locale
        $locale = str_replace('-', '_', $parts[0][0]);
        $request->setLocale($locale);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
