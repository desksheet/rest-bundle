<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\EventListener;

use Desksheet\RestBundle\Exception\ValidationException;
use Desksheet\RestBundle\Serializer\JMS\Handler\ProblemHandler;
use JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ExceptionListener
{
    use EventListenerTrait;

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly SerializationContextFactoryInterface $serializationContextFactory,
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        if (!$this->supports($event)) {
            return;
        }

        $exception = $event->getThrowable();
        $context   = $this->serializationContextFactory->createSerializationContext();
        if ($exception instanceof ValidationException) {
            $context->setAttribute(ProblemHandler::TITLE, $exception->getTitle());
            $context->setAttribute(ProblemHandler::VIOLATIONS, $exception->getConstraintViolationList());
        }

        $flattenException = FlattenException::createFromThrowable($exception);

        $event->setResponse(new Response(
            $this->serializer->serialize($flattenException, 'json', $context),
            $flattenException->getStatusCode(),
            $flattenException->getHeaders() + [
                'Content-Type' => 'application/json; charset=utf-8',
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'deny',
            ],
        ));
    }
}
