<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\Normalizer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * This Normalizer implements RFC7807 {@link https://tools.ietf.org/html/rfc7807}.
 */
class ProblemNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public const TITLE      = 'title';
    public const TYPE       = 'type';
    public const STATUS     = 'status';
    public const VIOLATIONS = 'violations';

    public function __construct(private readonly bool $debug = false, private readonly ?NameConverterInterface $nameConverter = null, private array $defaultContext = [])
    {
        $this->defaultContext = [
            self::TYPE => 'https://tools.ietf.org/html/rfc2616#section-10',
            self::TITLE => 'An error occurred',
        ];
    }

    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        if (!$object instanceof FlattenException) {
            throw new InvalidArgumentException(sprintf('The object must implement "%s".', FlattenException::class));
        }

        $context += $this->defaultContext;
        $debug    = $this->debug && ($context['debug'] ?? true);

        $data = [
            self::TYPE => $context['type'],
            self::TITLE => $context['title'],
            self::STATUS => $context['status'] ?? $object->getStatusCode(),
            'detail' => $debug ? $object->getMessage() : $object->getStatusText(),
        ];

        if (isset($context['violations']) && $context['violations'] instanceof ConstraintViolationListInterface) {
            $debug = false;
            foreach ($context['violations'] as $violation) {
                $data[self::VIOLATIONS][] = [
                    'propertyPath' => $this->nameConverter ? $this->nameConverter->normalize($violation->getPropertyPath()) : $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                    'code' => $violation->getCode(),
                ];
            }
        }

        if ($debug) {
            $data['class'] = $object->getClass();
            $data['trace'] = $object->getTrace();
        }

        return $data;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return self::class === static::class;
    }
}
