<?php

namespace SlmQueueAmq\Queue;

use SlmQueueAmq\Service\StompClientInterface;
use SlmQueueAmq\Options\QueueOptions;
use SlmQueue\Job\JobInterface;
use SlmQueue\Job\JobPluginManager;
use SlmQueue\Queue\AbstractQueue;

/**
 * AmqQueue
 */
class AmqQueue extends AbstractQueue implements AmqQueueInterface
{
    /**
     * @var StompClientInterface
     */
    protected $stompClient;

    /**
     * @var QueueOptions
     */
    protected $options;

    /**
     * Constructor
     *
     * @param StompClientInterface $stompClient
     * @param QueueOptions         $options
     * @param string               $name
     * @param JobPluginManager     $jobPluginManager
     */
    public function __construct(
        StompClientInterface $stompClient,
        QueueOptions $options,
        $name,
        JobPluginManager $jobPluginManager
    ) {
        $this->stompClient = $stompClient;
        $this->options     = $options;
        parent::__construct($name, $jobPluginManager);
    }

    /**
     * Valid options are:
     *      - self::REPEAT: the number of times this job must be repeated
     *      - self::PERIOD: the time to run (in milliseconds) a job can run
     *      - self::DELAY:  the time it takes (in milliseconds) before a job is available to a worker
     *      - self::CRON:   a cron pattern to execute the job at given moments
     *
     * {@inheritDoc}
     */
    public function push(JobInterface $job, array $options = array())
    {
        $allowed = [self::DELAY, self::PERIOD, self::REPEAT, self::CRON, self::PERSISTENT];
        $options = array_intersect_key($options, array_flip($allowed));

        // ActiveMQ STOMP expects a string "true" for persistency
        if (array_key_exists(self::PERSISTENT, $options) && true === (bool) $options[self::PERSISTENT]) {
            $options[self::PERSISTENT] = 'true';
        } elseif (array_key_exists(self::PERSISTENT, $options)) {
            unset($options[self::PERSISTENT]);
        }

        $this->ensureConnection();

        $this->stompClient->send(
            $this->options->getDestination(),
            $this->serializeJob($job),
            $options
        );
    }

    /**
     * {@inheritDoc}
     */
    public function pop(array $options = array())
    {
        $this->stompClient->subscribe($this->options->getDestination());
        if (array_key_exists('timeout', $options)) {
            $this->stompClient->setReadTimeout((int) $options['timeout'], 0);
        }

        $frame = $this->stompClient->readFrame();

        if ($frame === false) {
            return null;
        }

        $metadata = ['__id__' => $frame->headers['message-id'], 'heades' => $frame->headers];
        return $this->unserializeJob($frame->body, $metadata);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(JobInterface $job)
    {
        $this->stompClient->ack($job->getId());
    }

    public function ensureConnection()
    {
        if (!$this->stompClient->isConnected()) {
            $this->stompClient->connect();
        }
    }
}
