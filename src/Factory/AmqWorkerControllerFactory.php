<?php

namespace SlmQueueAmq\Factory;

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
        $worker  = $serviceLocator->getServiceLocator()->get('SlmQueueAmq\Worker\AmqWorker');
        $manager = $serviceLocator->getServiceLocator()->get('SlmQueue\Queue\QueuePluginManager');

        return new AmqWorkerController($worker, $manager);
    }
}
