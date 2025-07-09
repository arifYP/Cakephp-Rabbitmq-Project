<?php
namespace App\Command;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Command\Command;
use Cake\ORM\TableRegistry;

class RabbitWorkerCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('data_queue', false, false, false, false);

        $callback = function ($msg) use ($io) {
            $io->out('Received: ' . $msg->body);
            $data = json_decode($msg->body, true);

            $usersTable = TableRegistry::getTableLocator()->get('Users');
            $user = $usersTable->newEntity([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
            if ($usersTable->save($user)) {
                $io->out('Saved user: ' . $user->name);
            } else {
                $io->err('Failed to save user');
            }
        };

        $channel->basic_consume('data_queue', '', false, true, false, false, $callback);

        $io->out("Waiting for messages...");
        while ($channel->is_consuming()) {
            $channel->wait();
        }

        return static::CODE_SUCCESS;
    }
}
