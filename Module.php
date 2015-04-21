<?php

namespace SlmQueueAmq;

use Zend\ModuleManager\Feature;
use Zend\Console\Adapter\AdapterInterface;

/**
 * SlmQueueAmq
 */
class Module implements
    Feature\ConfigProviderInterface,
    Feature\ConsoleBannerProviderInterface,
    Feature\ConsoleUsageProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getConsoleBanner(AdapterInterface $console)
    {
        return 'SlmQueueAmq';
    }

    /**
     * {@inheritDoc}
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return array(
            'queue amq <queue> [--timeout=]' => 'Process jobs with Active MQ',

            array('<queue>', 'Queue\'s name ("destination") to process'),
            array('--timeout=', 'Timeout (in seconds) to wait for a job to arrive')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getModuleDependencies()
    {
        return array('SlmQueue');
    }
}
