<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Response;

use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Serializer\Annotation\Ignore;

class Response implements ResponseInterface
{
    protected int $statusCode          = HttpFoundationResponse::HTTP_OK;
    protected array $headers           = [];
    protected array $serializerContext = [];

    #[Ignore] public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    #[Ignore] public function getHeaders(): array
    {
        return $this->headers + ['Content-Type' => 'application/json; charset=utf-8'];
    }

    #[Ignore] public function getSerializerContext(): array
    {
        return $this->serializerContext;
    }
}
