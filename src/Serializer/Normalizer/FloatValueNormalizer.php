<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\Normalizer;

use Desksheet\RestBundle\Serializer\Type\FloatValue;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FloatValueNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): float
    {
        if (!$object instanceof FloatValue) {
            throw new InvalidArgumentException(sprintf('The object must implement the "%s".', FloatValue::class));
        }

        return $object->value;
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof FloatValue;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): FloatValue
    {
        if (is_float($data)) {
            return new FloatValue($data);
        }

        if (is_string($data)) {
            if (($value = filter_var($data, FILTER_VALIDATE_FLOAT)) === false) {
                throw new InvalidArgumentException(sprintf('Cannot extract float from "%s".', $data));
            }

            return new FloatValue($value);
        }

        throw new InvalidArgumentException(sprintf('Data should be with type of "float", "%s" given.', gettype($data)));
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null): bool
    {
        return $type === FloatValue::class;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return self::class === static::class;
    }
}
