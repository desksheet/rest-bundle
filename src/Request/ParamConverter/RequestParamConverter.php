<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Request\ParamConverter;

use Desksheet\RestBundle\Exception\ValidationException;
use Desksheet\RestBundle\Request\RequestBodyInterface;
use Desksheet\RestBundle\Request\RequestFormInterface;
use Desksheet\RestBundle\Request\RequestInterface;
use Desksheet\RestBundle\Request\RequestQueryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RequestParamConverter implements ParamConverterInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ?ValidatorInterface $validator,
    ) {
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $data = null;
        if (is_subclass_of($configuration->getClass(), RequestBodyInterface::class)) {
            $data = $request->getContent();
        }

        if (is_subclass_of($configuration->getClass(), RequestQueryInterface::class)) {
            $data = $request->query->all();
        }

        if (is_subclass_of($configuration->getClass(), RequestFormInterface::class)) {
            $data = $request->request->all() + $request->files->all();
        }

        if ($data === null) {
            return false;
        }

        try {
            $object = $this->serializer->deserialize(
                is_string($data) ? $data : json_encode($data),
                $configuration->getClass(),
                'json',
            );
            assert($object instanceof RequestInterface);
        } catch (ExceptionInterface $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        if ($this->validator) {
            $errors = $this->validator->validate($object);
            if ($errors->count()) {
                throw new ValidationException($errors);
            }
        }

        $request->attributes->set($configuration->getName(), $object);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return is_subclass_of($configuration->getClass(), RequestInterface::class);
    }
}
