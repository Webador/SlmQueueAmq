<?php

namespace SlmQueueAmqTest\Worker;

use PHPUnit_Framework_TestCase as TestCase;
use SlmQueue\Worker\WorkerEvent;
use SlmQueueAmq\Worker\AmqWorker;

class AmqWorkerTest extends TestCase
{
    /**
     * @var AmqWorker
     */
    protected $worker;

    public function setUp()
    {
        $this->worker = new AmqWorker($this->getMock('Zend\EventManager\EventManagerInterface'));
    }

    public function testReturnsUnknownIfNotAAmqQueue()
    {
        $queue = $this->getMock('SlmQueue\Queue\QueueInterface');
        $job   = $this->getMock('SlmQueue\Job\JobInterface');

        $status = $this->worker->processJob($job, $queue);
        $this->assertEquals(WorkerEvent::JOB_STATUS_UNKNOWN, $status);
    }

    public function testWorkerEstablishesConnectionOnProcessing()
    {
        $this->markTestSkipped('Skipped as processing queue keeps polling');

        $queue = $this->getMock('SlmQueueAmq\Queue\AmqQueueInterface');
        $job   = $this->getMock('SlmQueue\Job\JobInterface');

        $queue->expects($this->once())
              ->method('ensureConnection');

        $this->worker->processQueue($queue);
    }

    public function testDeleteJobOnSuccess()
    {
        $queue = $this->getMock('SlmQueueAmq\Queue\AmqQueueInterface');
        $job   = $this->getMock('SlmQueue\Job\JobInterface');

        $job->expects($this->once())
            ->method('execute');

        $queue->expects($this->once())
              ->method('delete')
              ->with($job);

        $status = $this->worker->processJob($job, $queue);
        $this->assertEquals(WorkerEvent::JOB_STATUS_SUCCESS, $status);
    }

    public function testDoNotDeleteJobOnFailure()
    {
        $queue = $this->getMock('SlmQueueAmq\Queue\AmqQueueInterface');
        $job   = $this->getMock('SlmQueue\Job\JobInterface');

        $job->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new \RuntimeException()));

        $queue->expects($this->never())
              ->method('delete');

        $status = $this->worker->processJob($job, $queue);
        $this->assertEquals(WorkerEvent::JOB_STATUS_FAILURE_RECOVERABLE, $status);
    }
}
