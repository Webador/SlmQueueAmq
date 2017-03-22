<?php

namespace SlmQueueAmq\Factory;

use Interop\Container\ContainerInterface;
use SlmQueueAmq\Controller\AmqWorkerController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * AmqWorkerControllerFactory
 */
class AmqWorkerControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, AmqWorkerController::class);
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

        $worker  = $container->get('SlmQueueAmq\Worker\AmqWorker');
        $manager = $container->get('SlmQueue\Queue\QueuePluginManager');

        return new AmqWorkerController($worker, $manager);
    }
}
