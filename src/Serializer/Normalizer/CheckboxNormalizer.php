<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\Normalizer;

use Desksheet\RestBundle\Serializer\Type\Checkbox;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CheckboxNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): Checkbox
    {
        if (is_string($data)) {
            return new Checkbox(filter_var($data, FILTER_VALIDATE_BOOLEAN));
        }

        if (!is_bool($data)) {
            throw new InvalidArgumentException(sprintf('Data should be with type of "boolean", "%s" given.', gettype($data)));
        }

        return new Checkbox($data);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null): bool
    {
        return $type === Checkbox::class;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return self::class === static::class;
    }

    public function normalize(mixed $object, ?string $format = null, array $context = []): bool
    {
        if (!$object instanceof Checkbox) {
            throw new InvalidArgumentException(sprintf('The object must implement the "%s".', Checkbox::class));
        }

        return $object->value;
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof Checkbox;
    }
}
