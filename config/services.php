<?php

declare(strict_types=1);

use Desksheet\RestBundle\EventListener\ExceptionListener;
use Desksheet\RestBundle\EventListener\ViewListener;
use Desksheet\RestBundle\Request\ParamConverter\RequestParamConverter;
use Desksheet\RestBundle\Serializer\Normalizer\BooleanTypeNormalizer;
use Desksheet\RestBundle\Serializer\Normalizer\FloatTypeNormalizer;
use Desksheet\RestBundle\Serializer\Normalizer\IntegerTypeNormalizer;
use Desksheet\RestBundle\Serializer\Normalizer\ProblemNormalizer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->services()
        // Normalizers
        ->set('desksheet.rest.normalizer.problem', ProblemNormalizer::class)
            ->args([param('kernel.debug'), service('serializer.name_converter.metadata_aware')->nullOnInvalid(), []])
            ->tag('serializer.normalizer', ['priority' => -890])
        ->set('desksheet.rest.normalizer.boolean_type', BooleanTypeNormalizer::class)
            ->tag('serializer.normalizer', ['priority' => -915])
        ->set('desksheet.rest.normalizer.integer_type', IntegerTypeNormalizer::class)
            ->tag('serializer.normalizer', ['priority' => -915])
        ->set('desksheet.rest.normalizer.float_type', FloatTypeNormalizer::class)
            ->tag('serializer.normalizer', ['priority' => -915])
        // Param converters
        ->set('desksheet.rest.request.param_converter', RequestParamConverter::class)
            ->args([service('serializer')->ignoreOnInvalid(), service('validator')->nullOnInvalid()])
            ->tag('request.param_converter', ['converter' => 'desksheet_rest_request_converter'])
        // Event listeners
        ->set('desksheet.rest.exception_listener', ExceptionListener::class)
            ->args([param('kernel.debug'), service('serializer')->ignoreOnInvalid()])
            ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'priority' => -10])
        ->set('desksheet.rest.view_listener', ViewListener::class)
            ->args([service('serializer')->ignoreOnInvalid()])
            ->tag('kernel.event_listener', ['event' => 'kernel.view'])
        // Override services
        ->set('serializer.normalizer.problem', 'desksheet.rest.normalizer.problem');
};
