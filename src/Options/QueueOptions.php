<?php

namespace SlmQueueAmq\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * QueueOptions
 */
class QueueOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $destination;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * Set the queue destionation
     *
     * @param  string $destination
     * @return void
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    /**
     * Get the queue destionation
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Getter for clientId
     *
     * @return string|null
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Setter for clientId
     *
     * @param  string $clientId Value to set
     * @return self
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }
}
