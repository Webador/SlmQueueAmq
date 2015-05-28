<?php

namespace SlmQueueAmq\Service;

use FuseSource\Stomp\Stomp;

class StompClient extends Stomp implements StompClientInterface
{
    public function getSubscriptions()
    {
        return $this->_subscriptions;
    }
}
