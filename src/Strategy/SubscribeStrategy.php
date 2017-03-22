<?php

namespace SlmQueueAmq\Strategy;

use SlmQueue\Strategy\AbstractStrategy;
use SlmQueue\Worker\Event\BootstrapEvent;
use SlmQueue\Worker\Event\WorkerEventInterface;
use SlmQueueAmq\Queue\AmqQueueInterface;
use SlmQueueAmq\Worker\Exception\InvalidQueueException;
use Zend\EventManager\EventManagerInterface;

class SubscribeStrategy extends AbstractStrategy
{
    protected $state;

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            WorkerEventInterface::EVENT_BOOTSTRAP,
            array($this, 'connectAndSubscribe'),
            $priority
        );
    }

    /**
     * Connect and subscribe to the AMQ queue
     *
     * @param BootstrapEvent $event
     */
    public function connectAndSubscribe(BootstrapEvent $event)
    {
        $queue = $event->getQueue();
        if (!$queue instanceof AmqQueueInterface) {
            throw InvalidQueueException::fromInvalidQueue($queue);
        }

        $queue->ensureConnection();
        $queue->subscribe();
    }
}
