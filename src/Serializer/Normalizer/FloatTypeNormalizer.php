<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\Normalizer;

use Desksheet\RestBundle\Serializer\Type\FloatType;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FloatTypeNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): float
    {
        if (!$object instanceof FloatType) {
            throw new InvalidArgumentException(sprintf('The object must implement the "%s".', FloatType::class));
        }

        return $object->value;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof FloatType;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): FloatType
    {
        if (is_float($data)) {
            return new FloatType($data);
        }

        if (is_string($data)) {
            if (($value = filter_var($data, FILTER_VALIDATE_FLOAT)) === false) {
                throw new InvalidArgumentException(sprintf('Cannot extract float from "%s".', $data));
            }

            return new FloatType($value);
        }

        throw new InvalidArgumentException(sprintf('Data should be with type of "float", "%s" given.', gettype($data)));
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === FloatType::class;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return self::class === static::class;
    }
}
