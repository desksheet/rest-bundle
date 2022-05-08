<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Exception;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends UnprocessableEntityHttpException implements \Stringable
{
    public function __construct(
        private readonly ConstraintViolationListInterface $constraintViolationList,
        private readonly string $title = 'Validation Failed',
        string $message = '',
        ?\Throwable $previous = null,
        int $code = 0,
        array $headers = [],
    ) {
        parent::__construct($message ?: $this->__toString(), $previous, $code, $headers);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }

    public function __toString(): string
    {
        $message = '';
        foreach ($this->constraintViolationList as $violation) {
            if ($message !== '') {
                $message .= "\n";
            }

            if ($propertyPath = $violation->getPropertyPath()) {
                $message .= "$propertyPath: ";
            }

            $message .= $violation->getMessage();
        }

        return $message;
    }
}
