<?php

namespace SlmQueueAmq\Factory;

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
        $options = $serviceLocator->get('SlmQueueAmq\Options\AmqOptions');
        return new StompClient($options->getBrokerUrl());
    }
}
