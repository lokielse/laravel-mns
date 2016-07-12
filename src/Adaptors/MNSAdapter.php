<?php

namespace Lokielse\LaravelMNS\Adaptors;

use Aliyun\MNS\AsyncCallback;
use Aliyun\MNS\Client;
use Aliyun\MNS\Model\QueueAttributes;
use Aliyun\MNS\Queue;
use Aliyun\MNS\Requests\BatchDeleteMessageRequest;
use Aliyun\MNS\Requests\BatchPeekMessageRequest;
use Aliyun\MNS\Requests\BatchReceiveMessageRequest;
use Aliyun\MNS\Requests\BatchSendMessageRequest;
use Aliyun\MNS\Requests\SendMessageRequest;
use Aliyun\MNS\Responses\BatchDeleteMessageResponse;
use Aliyun\MNS\Responses\BatchPeekMessageResponse;
use Aliyun\MNS\Responses\BatchReceiveMessageResponse;
use Aliyun\MNS\Responses\BatchSendMessageResponse;
use Aliyun\MNS\Responses\ChangeMessageVisibilityResponse;
use Aliyun\MNS\Responses\GetQueueAttributeResponse;
use Aliyun\MNS\Responses\MnsPromise;
use Aliyun\MNS\Responses\PeekMessageResponse;
use Aliyun\MNS\Responses\ReceiveMessageResponse;
use Aliyun\MNS\Responses\SendMessageResponse;
use Aliyun\MNS\Responses\SetQueueAttributeResponse;

/**
 * Class MNSAdapter
 *
 * @method string getUsing()
 * @method SetQueueAttributeResponse setAttribute( QueueAttributes $attributes )
 * @method MnsPromise setAttributeAsync( QueueAttributes $attributes, AsyncCallback $callback = null )
 * @method GetQueueAttributeResponse getAttribute( QueueAttributes $attributes )
 * @method MnsPromise getAttributeAsync( QueueAttributes $attributes, AsyncCallback $callback = null )
 * @method SendMessageResponse sendMessage( SendMessageRequest $request )
 * @method MnsPromise sendMessageAsync( SendMessageRequest $request, AsyncCallback $callback = null )
 * @method PeekMessageResponse peekMessage()
 * @method MnsPromise peekMessageAsync( AsyncCallback $callback = null )
 * @method ReceiveMessageResponse receiveMessage( int $waitSeconds = null )
 * @method MnsPromise receiveMessageAsync( AsyncCallback $callback = null )
 * @method ReceiveMessageResponse deleteMessage( string $receiptHandle )
 * @method MnsPromise deleteMessageAsync( string $receiptHandle, AsyncCallback $callback = null )
 * @method ChangeMessageVisibilityResponse changeMessageVisibility( string $receiptHandle, int $visibilityTimeout )
 * @method BatchSendMessageResponse batchSendMessage( BatchSendMessageRequest $request )
 * @method MnsPromise batchSendMessageAsync( BatchSendMessageRequest $request, AsyncCallback $callback = null )
 * @method BatchReceiveMessageResponse batchReceiveMessage( BatchReceiveMessageRequest $request )
 * @method MnsPromise batchReceiveMessageAsync( BatchReceiveMessageRequest $request, AsyncCallback $callback = null )
 * @method BatchPeekMessageResponse batchPeekMessage( BatchPeekMessageRequest $request )
 * @method MnsPromise batchPeekMessageAsync( BatchPeekMessageRequest $request, AsyncCallback $callback = null )
 * @method BatchDeleteMessageResponse batchDeleteMessage( BatchDeleteMessageRequest $request )
 * @method MnsPromise batchDeleteMessageAsync( BatchDeleteMessageRequest $request, AsyncCallback $callback = null )
 */
class MNSAdapter
{

    /**
     * Aliyun MNS Client
     *
     * @var Client
     */
    private $client;

    /**
     * Aliyun MNS SDK Queue.
     *
     * @var Queue
     */
    private $queue;

    private $using;


    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([ $this->queue, $method ], $parameters);
    }


    /**
     * @param string $queue
     *
     * @return self
     */
    public function useQueue($queue)
    {
        if ($this->using != $queue) {
            $this->using = $queue;
            $this->queue = $this->client->getQueueRef($queue);
        }

        return $this;
    }
}