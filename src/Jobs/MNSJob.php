<?php

namespace Lokielse\LaravelMNS\Jobs;

use Aliyun\MNS\Responses\ReceiveMessageResponse;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Queue\Jobs\Job;
use Lokielse\LaravelMNS\Adaptors\MNSAdapter;

class MNSJob extends Job implements JobContract
{

    /**
     * The class name of the job.
     *
     * @var string
     */
    protected $job;

    /**
     * The queue message data.
     *
     * @var string
     */
    protected $data;

    /**
     * @var MNSAdapter
     */
    private $adapter;


    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Container\Container              $container
     * @param MNSAdapter                                   $mns
     * @param string                                       $queue
     * @param \Aliyun\MNS\Responses\ReceiveMessageResponse $job
     */
    public function __construct(Container $container, MNSAdapter $mns, $queue, ReceiveMessageResponse $job)
    {
        $this->container = $container;
        $this->adapter   = $mns;
        $this->queue     = $queue;
        $this->job       = $job;
    }


    /**
     * Fire the job.
     */
    public function fire()
    {
        if (method_exists($this, 'resolveAndFire')) {
            $payload = json_decode($this->getRawBody(), true);

            if ( ! is_array($payload)) {
                throw new \InvalidArgumentException("Seems it's not a Laravel enqueued job. [$payload]");
            }

            $this->resolveAndFire($payload);
        } else {
            parent::fire();
        }
    }


    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        return $this->job->getMessageBody();
    }


    /**
     * Delete the job from the queue.
     */
    public function delete()
    {
        parent::delete();
        $receiptHandle = $this->job->getReceiptHandle();
        $this->adapter->useQueue($this->getQueue())->deleteMessage($receiptHandle);
    }


    /**
     * Release the job back into the queue.
     *
     * @param int $delay
     */
    public function release($delay = 1)
    {
        parent::release($delay);

        if ($delay < 1) {
            $delay = 1;
        }

        $this->adapter->useQueue($this->getQueue())->changeMessageVisibility($this->job->getReceiptHandle(), $delay);
    }


    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        return (int) $this->job->getDequeueCount();
    }


    /**
     * Get the IoC container instance.
     *
     * @return \Illuminate\Container\Container
     */
    public function getContainer()
    {
        return $this->container;
    }


    /**
     * Get the Job ID.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->job->getMessageId();
    }
}
