<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\Type;

final class Checkbox
{
    public function __construct(public readonly bool $value)
    {
    }
}
