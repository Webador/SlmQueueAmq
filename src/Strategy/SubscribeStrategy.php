<?php

namespace SlmQueueAmq\Strategy;

use SlmQueue\Strategy\AbstractStrategy;
use SlmQueue\Worker\WorkerEvent;
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
            WorkerEvent::EVENT_BOOTSTRAP,
            array($this, 'connectAndSubscribe'),
            $priority
        );
    }

    /**
     * Connect and subscribe to the AMQ queue
     *
     * @param WorkerEvent $event
     */
    public function connectAndSubscribe(WorkerEvent $event)
    {
        $queue = $event->getQueue();
        if (!$queue instanceof AmqQueueInterface) {
            throw new InvalidQueueException;
        }

        $queue->ensureConnection();
        $queue->subscribe();
    }
}
