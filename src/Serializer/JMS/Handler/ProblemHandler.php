<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\JMS\Handler;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ProblemHandler implements SubscribingHandlerInterface
{
    public const TITLE      = 'title';
    public const TYPE       = 'type';
    public const STATUS     = 'status';
    public const VIOLATIONS = 'violations';

    public function __construct(private readonly bool $debug = false)
    {
    }

    public static function getSubscribingMethods(): array
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'type' => FlattenException::class,
                'format' => 'json',
                'method' => 'serializeFlattenExceptionToJson',
            ],
        ];
    }

    public function serializeFlattenExceptionToJson(SerializationVisitorInterface $visitor, FlattenException $exception, array $type, SerializationContext $context): array
    {
        $debug = $this->debug && ($this->getAttributeFromContext($context, 'debug', true));

        $data = [
            'type' => $this->getAttributeFromContext($context, self::TYPE, 'https://tools.ietf.org/html/rfc2616#section-10'),
            'title' => $this->getAttributeFromContext($context, self::TITLE, 'An error occurred'),
            'status' => $this->getAttributeFromContext($context, self::STATUS, $exception->getStatusCode()),
            'detail' => $debug ? $exception->getMessage() : $exception->getStatusText(),
        ];

        /** @psalm-suppress MixedAssignment */
        $violations = $this->getAttributeFromContext($context, self::VIOLATIONS);
        if ($violations instanceof ConstraintViolationListInterface) {
            $data['violations'] = $violations;
        }

        if ($debug) {
            $data['class'] = $exception->getClass();
            $data['trace'] = $exception->getTrace();
        }

        return $data;
    }

    /**
     * @param int|string|true|null $default
     * @psalm-param An error occurred|https://tools.ietf.org/html/rfc2616#section-10|int|true|null $default
     */
    private function getAttributeFromContext(SerializationContext $context, string $name, string|bool|int|nulld|null $default = null): mixed
    {
        return $context->hasAttribute($name) ? $context->getAttribute($name) : $default;
    }
}
