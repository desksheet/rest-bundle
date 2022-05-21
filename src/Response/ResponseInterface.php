<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Response;

use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\SerializationContext;

interface ResponseInterface
{
    #[Exclude] public function getStatusCode(): int;

    #[Exclude] public function getHeaders(): array;

    #[Exclude] public function getSerializerContext(): SerializationContext;
}
