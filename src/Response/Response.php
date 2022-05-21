<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Response;

use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class Response implements ResponseInterface
{
    protected int $statusCode = HttpFoundationResponse::HTTP_OK;
    /** @var array<string, string> */
    protected array $headers = [];

    protected ?SerializationContext $serializerContext = null;

    #[Exclude] public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    #[Exclude] public function getHeaders(): array
    {
        return $this->headers + ['Content-Type' => 'application/json; charset=utf-8'];
    }

    #[Exclude] public function getSerializerContext(): SerializationContext
    {
        return $this->serializerContext ?? new SerializationContext();
    }
}
