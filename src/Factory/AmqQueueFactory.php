<?php

namespace SlmQueueAmq\Factory;

use SlmQueueAmq\Queue\AmqQueue;
use SlmQueueAmq\Options\QueueOptions;
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

        $config           = $parentLocator->get('Config')['slm_queue']['queues'];
        $queueConfig      = array_key_exists($requestedName, $config) ? $config[$requestedName] : [];
        $queueOptions     = new QueueOptions($queueConfig);

        $stompClient      = $parentLocator->get('SlmQueueAmq\Service\StompClient');
        $jobPluginManager = $parentLocator->get('SlmQueue\Job\JobPluginManager');

        if (null !== $queueOptions->getClientId()) {
            $stompClient->clientId = $queueOptions->getClientId();
        }

        return new AmqQueue($stompClient, $queueOptions, $requestedName, $jobPluginManager);
    }
}
