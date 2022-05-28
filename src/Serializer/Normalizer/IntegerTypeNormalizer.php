<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\Normalizer;

use Desksheet\RestBundle\Serializer\Type\IntegerType;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IntegerTypeNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): int
    {
        if (!$object instanceof IntegerType) {
            throw new InvalidArgumentException(sprintf('The object must implement the "%s".', IntegerType::class));
        }

        return $object->value;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof IntegerType;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): IntegerType
    {
        if (is_int($data)) {
            return new IntegerType($data);
        }

        if (is_string($data)) {
            if (($value = filter_var($data, FILTER_VALIDATE_INT)) === false) {
                throw new InvalidArgumentException(sprintf('Cannot extract integer from "%s".', $data));
            }

            return new IntegerType($value);
        }

        throw new InvalidArgumentException(sprintf('Data should be with type of "integer", "%s" given.', gettype($data)));
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === IntegerType::class;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return self::class === static::class;
    }
}
