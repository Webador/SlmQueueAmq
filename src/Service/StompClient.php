<?php

namespace SlmQueueAmq\Service;

use Stomp;

class StompClient implements StompClientInterface
{
    protected $subscriptions = [];
    protected $stomp;
    protected $broker;

    public function __construct($broker)
    {
        $this->broker = $broker;
    }

    public function isConnected()
    {
        return (null !== $this->stomp);
    }

    public function connect($username = '', $password = '')
    {
        $this->stomp = new Stomp($this->broker, $username, $password);
    }

    public function send($destination, $message, $properties = [], $sync = null)
    {
        $this->stomp->send($destination, $message, $properties);
    }

    public function subscribe($destination, $properties = [], $sync = null)
    {
        $this->stomp->subscribe($destination, $properties);
        $this->subscriptions[$destination] = $properties;
    }

    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    public function unsubscribe($destination, $properties = [], $sync = null)
    {
        $this->stomp->unsubscribe($destination, $properties);
    }

    public function ack($message, $transactionId = null)
    {
        $headers = ($transactionId) ? ['transaction' => $transactionId] : [];
        $this->stomp->ack($message, $headers);
    }

    public function disconnect()
    {
        unset($this->stomp);
    }

    public function readFrame()
    {
        return $this->stomp->readFrame();
    }

    public function setReadTimeout($seconds, $milliseconds = 0)
    {
        $this->stomp->setReadTimeout($seconds, $milliseconds);
    }

    public function hasFrameToRead()
    {
        return $this->stomp->hasFrame();
    }
}
