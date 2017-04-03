<?php

namespace SlmQueueAmq\Factory;

use Interop\Container\ContainerInterface;
use SlmQueueAmq\Service\StompClient;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StompClientFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, StompClient::class);
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = $container->get('SlmQueueAmq\Options\AmqOptions');
        return new StompClient($options->getBrokerUrl());
    }
}
