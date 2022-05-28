<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\Type;

/**
 * Use this object value type if you want to convert string values to booleans too
 * Example: "yes" => TRUE, "on" => TRUE, "false" => FALSE...
 */
final class BooleanType
{
    public function __construct(public readonly bool $value)
    {
    }
}
