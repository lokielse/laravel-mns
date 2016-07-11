<?php

namespace Lokielse\LaravelMNS\Console;

use Aliyun\MNS\Client;
use Aliyun\MNS\Requests\BatchReceiveMessageRequest;
use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MNSClearCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'queue:mns:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear MNS Queue';


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $queue      = $this->argument('queue');
        $connection = $this->option('connection');

        $config = config("queue.connections.{$connection}");

        if ( ! $queue) {
            $queue = $config['queue'];
        }

        $client = new Client($config['endpoint'], $config['key'], $config['secret']);
        $queue  = $client->getQueueRef($queue);

        $hasMessage = true;

        while ($hasMessage) {

            $this->info('Peeking messages (Polling...)');

            try {
                $response = $queue->batchPeekMessage(15);
                if ($response->getMessages()) {
                    $hasMessage = true;
                } else {
                    $hasMessage = false;
                }
            } catch (Exception $e) {
                $this->info('no messages');
                break;
            }

            $response = $queue->batchReceiveMessage(new BatchReceiveMessageRequest(15, 30));

            $handles = [ ];

            /**
             * @var  \Aliyun\MNS\Model\Message $message
             */
            foreach ($response->getMessages() as $message) {
                $handles[] = $message->getReceiptHandle();
            }

            $response = $queue->batchDeleteMessage($handles);

            if ($response->isSucceed()) {
                foreach ($handles as $handle) {
                    $this->info(sprintf("The message: %s deleted success", $handle));
                }
            }
        }
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            [ 'queue', InputArgument::OPTIONAL, 'The queue name' ],
        ];
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [ 'connection', 'c', InputOption::VALUE_OPTIONAL, 'The Queue connection name', 'mns' ]
        ];
    }
}
