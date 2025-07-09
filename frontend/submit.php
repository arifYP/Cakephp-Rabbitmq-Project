<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($name) || empty($email)) {
        http_response_code(400);
        echo "Name and Email are required.";
        exit;
    }

    try {
        $data = [
            'name' => $name,
            'email' => $email,
        ];

        $msg = new AMQPMessage(json_encode($data));

        $conn = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $ch = $conn->channel();

        $ch->queue_declare('data_queue', false, false, false, false);
        $ch->basic_publish($msg, '', 'data_queue');

        $ch->close();
        $conn->close();

        echo "✅ Data sent to queue!";
    } catch (Exception $e) {
        http_response_code(500);
        echo "❌ Error sending to RabbitMQ: " . $e->getMessage();
    }
} else {
    http_response_code(405);
    echo "Method Not Allowed";
}
?>
