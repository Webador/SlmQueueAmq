<?php

namespace SlmQueueAmq\Worker;

use Exception;
use SlmQueue\Job\JobInterface;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Worker\AbstractWorker;
use SlmQueue\Worker\WorkerEvent;
use SlmQueueAmq\Queue\AmqQueueInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * Worker for Amq
 */
class AmqWorker extends AbstractWorker
{
    /**
     * @param EventManagerInterface $eventManager
     */
    public function __construct(EventManagerInterface $eventManager)
    {
        parent::__construct($eventManager);

        $this->eventManager->attach(WorkerEvent::EVENT_BOOTSTRAP, [$this, 'connect']);
    }

    /**
     * {@inheritDoc}
     */
    public function processJob(JobInterface $job, QueueInterface $queue)
    {
        if (!$queue instanceof AmqQueueInterface) {
            return WorkerEvent::JOB_STATUS_UNKNOWN;
        }

        /**
         * In Amq, if an error occurs (exception for instance), the job
         * is automatically reinserted into the queue. If the job executed
         * correctly, it must explicitly be removed
         */
        try {
            $job->execute();
            $queue->delete($job);

            return WorkerEvent::JOB_STATUS_SUCCESS;
        } catch (Exception $exception) {
            // Do nothing, the job will be reinserted automatically for another try
            return WorkerEvent::JOB_STATUS_FAILURE_RECOVERABLE;
        }
    }

    /**
     * Connect to the Active MQ server when starting the queue process
     */
    public function connect(WorkerEvent $event)
    {
        $queue = $event->getQueue();
        if (!$queue instanceof AmqQueueInterface) {
            throw new Exception\InvalidQueueException;
        }

        $queue->ensureConnection();
        $queue->subscribe();
    }
}
