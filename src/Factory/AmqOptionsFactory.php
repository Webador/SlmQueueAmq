<?php

namespace SlmQueueAmq\Factory;

use Interop\Container\ContainerInterface;
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
        return $this($serviceLocator, AmqOptions::class);
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        return new AmqOptions($config['slm_queue']['active_mq']);
    }
}
