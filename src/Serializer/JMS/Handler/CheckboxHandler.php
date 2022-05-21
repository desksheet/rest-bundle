<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\JMS\Handler;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;

class CheckboxHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods(): array
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'checkbox',
                'method' => 'deserializeCheckboxFromJson',
            ],
        ];
    }

    public function deserializeCheckboxFromJson(DeserializationVisitorInterface $visitor, mixed $data): ?bool
    {
        if ($data === null) {
            return null;
        }

        return filter_var($data, FILTER_VALIDATE_BOOLEAN);
    }
}
