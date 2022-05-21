<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\Normalizer;

use Desksheet\RestBundle\Serializer\Type\BooleanValue;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BooleanValueNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): bool
    {
        if (!$object instanceof BooleanValue) {
            throw new InvalidArgumentException(sprintf('The object must implement the "%s".', BooleanValue::class));
        }

        return $object->value;
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof BooleanValue;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): BooleanValue
    {
        if (is_bool($data)) {
            return new BooleanValue($data);
        }

        if (is_string($data)) {
            return new BooleanValue(filter_var($data, FILTER_VALIDATE_BOOLEAN));
        }

        throw new InvalidArgumentException(sprintf('Data should be with type of "boolean", "%s" given.', gettype($data)));
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null): bool
    {
        return $type === BooleanValue::class;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return self::class === static::class;
    }
}
