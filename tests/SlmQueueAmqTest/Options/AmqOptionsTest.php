<?php

namespace SlmQueueAmqTest\Options;

use PHPUnit_Framework_TestCase as TestCase;
use SlmQueueAmqTest\Util\ServiceManagerFactory;
use Zend\ServiceManager\ServiceManager;

class AmqOptionsTest extends TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function setUp()
    {
        parent::setUp();
        $this->serviceManager = ServiceManagerFactory::getServiceManager();
    }

    public function testCreatedAmqOptions()
    {
        /** @var $amqOptions \SlmQueueAmq\Options\AmqOptions */
        $amqOptions = $this->serviceManager->get('SlmQueueAmq\Options\AmqOptions');

        $this->assertInstanceOf('SlmQueueAmq\Options\AmqOptions', $amqOptions);
        $this->assertEquals('tcp://localhost:61613', $amqOptions->getBrokerUrl());
    }
}
