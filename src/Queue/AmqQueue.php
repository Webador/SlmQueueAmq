<?php

namespace SlmQueueAmq\Queue;

use SlmQueueAmq\Service\StompClientInterface;
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
     * Constructor
     *
     * @param StompClientInterface $stompClient
     * @param string               $name
     * @param JobPluginManager     $jobPluginManager
     */
    public function __construct(StompClientInterface $stompClient, $name, JobPluginManager $jobPluginManager)
    {
        $this->stompClient = $stompClient;
        parent::__construct($name, $jobPluginManager);
    }

    /**
     * {@inheritDoc}
     */
    public function push(JobInterface $job, array $options = array())
    {
        $name = $this->getName();
        $json = $this->serializeJob($job);

        $this->ensureConnection();

        $this->stompClient->send(
            $this->getName(),
            $this->serializeJob($job),
            [] // headers, when needded
        );
    }

    /**
     * {@inheritDoc}
     */
    public function pop(array $options = array())
    {
        $this->stompClient->subscribe($this->getName());
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

    public function ensureConnection()
    {
        if (!$this->stompClient->isConnected()) {
            $this->stompClient->connect();
        }
    }
}
