<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Response;

use Symfony\Component\Serializer\Annotation\Ignore;

interface ResponseInterface
{
    #[Ignore]
    public function getStatusCode(): int;

    #[Ignore]
    public function getHeaders(): array;

    #[Ignore]
    public function getSerializerContext(): array;
}
