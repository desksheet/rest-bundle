<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\Normalizer;

use Desksheet\RestBundle\Serializer\Type\BooleanType;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BooleanTypeNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): bool
    {
        if (!$object instanceof BooleanType) {
            throw new InvalidArgumentException(sprintf('The object must implement the "%s".', BooleanType::class));
        }

        return $object->value;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof BooleanType;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): BooleanType
    {
        if (is_bool($data)) {
            return new BooleanType($data);
        }

        if (is_string($data)) {
            return new BooleanType(filter_var($data, FILTER_VALIDATE_BOOLEAN));
        }

        throw new InvalidArgumentException(sprintf('Data should be with type of "boolean", "%s" given.', gettype($data)));
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === BooleanType::class;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return self::class === static::class;
    }
}
