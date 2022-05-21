<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\EventListener;

use Desksheet\RestBundle\Response\ResponseInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class ViewListener
{
    use EventListenerTrait;

    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function __invoke(ViewEvent $event): void
    {
        if (!$this->supports($event)) {
            return;
        }

        $result = $event->getControllerResult();
        if (!$result instanceof ResponseInterface) {
            return;
        }

        $event->setResponse(new Response(
            $this->serializer->serialize($result, 'json', $result->getSerializerContext()),
            $result->getStatusCode(),
            $result->getHeaders(),
        ));
    }
}
