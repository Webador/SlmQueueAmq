<?php

namespace SlmQueueAmq\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * AmqOptions
 */
class AmqOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $brokerUrl;

    /**
     * Set the broker's url
     *
     * @param  string $url
     * @return void
     */
    public function setBrokerUrl($url)
    {
        $this->brokerUrl = $url;
    }

    /**
     * Get the broker's url
     *
     * @return string
     */
    public function getBrokerUrl()
    {
        return $this->brokerUrl;
    }
}
