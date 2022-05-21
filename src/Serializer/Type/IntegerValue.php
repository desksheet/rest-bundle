<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\Type;

/**
 * Use this object value type if you want to convert string value to integer too
 * Example: "123" => 123...
 */
final class IntegerValue
{
    public function __construct(public readonly int $value)
    {
    }
}
