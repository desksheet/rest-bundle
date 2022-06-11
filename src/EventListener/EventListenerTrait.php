<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\EventListener;

use Desksheet\RestBundle\Controller\ControllerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

trait EventListenerTrait
{
    private function supports(RequestEvent $event): bool
    {
        /** @psalm-suppress MixedArgument */
        return $event->isMainRequest() && !$event->hasResponse() && is_subclass_of($event->getRequest()->attributes->get('_controller'), ControllerInterface::class);
    }
}
