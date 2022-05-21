<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Serializer\JMS\Handler;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;

/**
 * Handler for the alphabetic characters
 */
class AlphaHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods(): array
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'alpha',
                'method' => 'deserializeAlphaFromJson',
            ],
        ];
    }

    public function deserializeAlphaFromJson(DeserializationVisitorInterface $visitor, mixed $data): ?string
    {
        if ($data === null) {
            return null;
        }

        return preg_replace('/[^[:alpha:]]/', '', $data);
    }
}
