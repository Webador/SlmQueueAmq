<?php

namespace SlmQueueAmq\Factory;

use Interop\Container\ContainerInterface;
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
        return $this($serviceLocator, AmqQueue::class);
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Grab parent container if available (ZF2)
        if (method_exists($container, 'getServiceLocator')) {
            $container = $container->getServiceLocator() ?: $container;
        }

        $config           = $container->get('Config')['slm_queue']['queues'];
        $queueConfig      = array_key_exists($requestedName, $config) ? $config[$requestedName] : [];
        $queueOptions     = new QueueOptions($queueConfig);

        $stompClient      = $container->get('SlmQueueAmq\Service\StompClient');
        $jobPluginManager = $container->get('SlmQueue\Job\JobPluginManager');

        if (null !== $queueOptions->getClientId()) {
            $stompClient->clientId = $queueOptions->getClientId();
        }

        return new AmqQueue($stompClient, $queueOptions, $requestedName, $jobPluginManager);
    }
}
