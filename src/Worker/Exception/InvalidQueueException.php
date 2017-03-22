<?php

namespace SlmQueueAmq\Worker\Exception;

use SlmQueue\Queue\QueueInterface;
use SlmQueueAmq\Exception\ExceptionInterface;
use InvalidArgumentException;
use SlmQueueAmq\Queue\AmqQueueInterface;

class InvalidQueueException extends InvalidArgumentException implements ExceptionInterface
{

    /**
     * @param QueueInterface $queue
     *
     * @return self
     */
    public static function fromInvalidQueue(QueueInterface $queue)
    {
        return new self(sprintf(
            'Event provided by %s was expected to be instance of %s; %s given',
            AmqQueueInterface::class,
            get_class($queue)
        ));
    }
}
