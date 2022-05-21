<?php

declare(strict_types=1);

use Desksheet\RestBundle\EventListener\ExceptionListener;
use Desksheet\RestBundle\EventListener\ViewListener;
use Desksheet\RestBundle\Request\ParamConverter\RequestParamConverter;
use Desksheet\RestBundle\Serializer\JMS\Handler\AlnumHandler;
use Desksheet\RestBundle\Serializer\JMS\Handler\AlphaHandler;
use Desksheet\RestBundle\Serializer\JMS\Handler\CheckboxHandler;
use Desksheet\RestBundle\Serializer\JMS\Handler\ProblemHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->services()
        // Serializer handlers
        ->set('desksheet.rest.serializer.handler.problem', ProblemHandler::class)
            ->args([param('kernel.debug')])
            ->tag('jms_serializer.subscribing_handler')
        ->set('desksheet.rest.serializer.handler.checkbox', CheckboxHandler::class)
            ->tag('jms_serializer.subscribing_handler')
        ->set('desksheet.rest.serializer.handler.alpha', AlphaHandler::class)
            ->tag('jms_serializer.subscribing_handler')
        ->set('desksheet.rest.serializer.handler.alnum', AlnumHandler::class)
            ->tag('jms_serializer.subscribing_handler')
        // Param converter
        ->set('desksheet.rest.request.param_converter', RequestParamConverter::class)
            ->args([service('jms_serializer')->ignoreOnInvalid(), service('validator')->nullOnInvalid()])
            ->tag('request.param_converter', ['converter' => 'desksheet_rest_request_converter'])
        // Event listeners
        ->set('desksheet.rest.exception_listener', ExceptionListener::class)
            ->args([service('jms_serializer')->ignoreOnInvalid(), service('jms_serializer.configured_serialization_context_factory')->ignoreOnInvalid()])
            ->tag('kernel.event_listener', ['event' => 'kernel.exception'])
        ->set('desksheet.rest.view_listener', ViewListener::class)
            ->args([service('jms_serializer')->ignoreOnInvalid()])
            ->tag('kernel.event_listener', ['event' => 'kernel.view']);
};
