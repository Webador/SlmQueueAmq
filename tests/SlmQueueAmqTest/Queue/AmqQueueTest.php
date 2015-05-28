<?php

namespace SlmQueueAmqTest\Queue;

use FuseSource\Stomp\Frame;
use PHPUnit_Framework_TestCase as TestCase;
use SlmQueueAmq\Queue\AmqQueue;
use SlmQueueAmq\Options\QueueOptions;
use SlmQueueAmqTest\Asset\SimpleJob;

/**
 * AmqQueue Test
 */
class AmqQueueTest extends TestCase
{
    protected $queueName;
    protected $stompClient;
    protected $options;
    protected $pluginManager;

    public function setUp()
    {
        $this->queueName     = 'testQueueName';
        $this->stompClient   = $this->getMock('SlmQueueAmq\Service\StompClientInterface');
        $this->options       = new QueueOptions;

        $this->pluginManager = $this->getMockBuilder('SlmQueue\Job\JobPluginManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->queue = new AmqQueue($this->stompClient, $this->options, $this->queueName, $this->pluginManager);
    }

    public function testPushConnectsBeforeSending()
    {
        $this->stompClient->expects($this->once())
                          ->method('isConnected')
                          ->will($this->returnValue(false));

        $this->stompClient->expects($this->once())
                          ->method('connect');

        $job = new SimpleJob;
        $this->queue->push($job);
    }

    public function testPushDoesConnectOnlyIfNotConnected()
    {
        $this->stompClient->expects($this->once())
                          ->method('isConnected')
                          ->will($this->returnValue(true));

        $this->stompClient->expects($this->never())
                          ->method('connect');

        $job = new SimpleJob;
        $this->queue->push($job);
    }

    public function testPushSendsJobToDestination()
    {
        $this->options->setDestination('queue/foo');

        $this->stompClient->expects($this->once())
                          ->method('send')
                          ->with('queue/foo');

        $job = new SimpleJob;
        $this->queue->push($job);
    }

    public function testPopReturnsNullWhenNoFrameIsRead()
    {
        $this->stompClient->expects($this->once())
                          ->method('readFrame')
                          ->will($this->returnValue(false));

        $this->queue->pop();
    }

    public function testPopSetsTimeoutWhenAvailable()
    {
        $this->stompClient->expects($this->once())
                          ->method('setReadTimeOut')
                          ->with(10, 0);

        $this->stompClient->expects($this->once())
                          ->method('readFrame')
                          ->will($this->returnValue(false));

        $this->queue->pop(['timeout' => 10]);
    }

    public function testPopExtractsMetadataFromFrame()
    {
        $frame = new Frame;
        $frame->headers = [
            'message-id' => 'ID:queue-56496-1430126920007-2:28:-1:1:1',
            'foo'        => 'bar'
        ];
        $frame->body = '{"content":"N;","metadata":{"__name__":"SlmQueueAmqTest\\\Asset\\\SimpleJob"}}';

        $this->stompClient->expects($this->once())
                          ->method('readFrame')
                          ->will($this->returnValue($frame));

        $job = new SimpleJob;
        $this->pluginManager->expects($this->once())
                               ->method('get')
                               ->will($this->returnValue($job));

        $result = $this->queue->pop();

        $this->assertInstanceOf('SlmQueueAmqTest\Asset\SimpleJob', $result);
        $this->assertEquals('ID:queue-56496-1430126920007-2:28:-1:1:1', $job->getId());
        $this->assertEquals($frame->headers, $job->getMetadata('heades'));
    }

    public function testDeleteAcknowledgesDelivery()
    {
        $this->stompClient->expects($this->once())
                          ->method('ack')
                          ->with(1234);

        $job = new SimpleJob;
        $job->setId(1234);
        $this->queue->delete($job);
    }
}
