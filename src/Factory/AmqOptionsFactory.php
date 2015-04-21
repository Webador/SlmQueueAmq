<?php

namespace SlmQueueAmq\Factory;

use SlmQueueAmq\Options\AmqOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AmqOptionsFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        return new AmqOptions($config['slm_queue']['active_mq']);
    }
}
