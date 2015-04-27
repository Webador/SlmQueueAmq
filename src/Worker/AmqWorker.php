<?php

namespace SlmQueueAmq\Worker;

use Exception;
use SlmQueue\Job\JobInterface;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Worker\AbstractWorker;
use SlmQueue\Worker\WorkerEvent;
use SlmQueueAmq\Queue\AmqQueueInterface;

/**
 * Worker for Amq
 */
class AmqWorker extends AbstractWorker
{
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
}
