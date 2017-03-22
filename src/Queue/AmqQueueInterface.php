<?php

namespace SlmQueueAmq\Queue;

use SlmQueue\Queue\QueueInterface;

interface AmqQueueInterface extends QueueInterface
{
    const DELAY      = 'AMQ_SCHEDULED_DELAY';
    const PERIOD     = 'AMQ_SCHEDULED_PERIOD';
    const REPEAT     = 'AMQ_SCHEDULED_REPEAT';
    const CRON       = 'AMQ_SCHEDULED_CRON';
    const PERSISTENT = 'persistent';

    public function ensureConnection();
    public function subscribe();
}
