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
}
