<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\Type;

/**
 * Use this object value type if you want to convert string value to float too
 * Example: "1.5640" => 1.564...
 */
final class FloatType
{
    public function __construct(public readonly float $value)
    {
    }
}
