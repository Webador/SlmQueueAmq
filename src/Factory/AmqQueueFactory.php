<?php

namespace SlmQueueAmq\Factory;

use SlmQueueAmq\Queue\AmqQueue;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * AmqQueueFactory
 */
class AmqQueueFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $name = '', $requestedName = '')
    {
        $parentLocator    = $serviceLocator->getServiceLocator();
        $stompClient      = $parentLocator->get('SlmQueueAmq\Service\StompClient');
        $jobPluginManager = $parentLocator->get('SlmQueue\Job\JobPluginManager');

        return new AmqQueue($stompClient, $requestedName, $jobPluginManager);
    }
}
