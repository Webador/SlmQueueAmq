<?php

namespace SlmQueueAmq\Service;

interface StompClientInterface
{
    /* Methods used from the Stomp client implementation */
    public function connect($username = '', $password = '');
    public function isConnected();
    public function send($destination, $message, $properties = [], $sync = null);
    public function subscribe($destination, $properties = [], $sync = null);
    public function unsubscribe($destination, $properties = [], $sync = null);
    public function begin($transactionId = null, $sync = null);
    public function commit($commit = null, $sync = null);
    public function abort($transactionId = null, $sync = null);
    public function ack($message, $transactionId = null);
    public function disconnect();
    public function readFrame();
    public function setReadTimeout($seconds, $milliseconds = 0);
    public function hasFrameToRead();

    /* Custom method */
    public function getSubscriptions();
}
