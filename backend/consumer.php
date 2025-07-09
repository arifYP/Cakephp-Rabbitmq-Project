<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

// ✅ RabbitMQ connection
$conn = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $conn->channel();

$channel->queue_declare('data_queue', false, false, false, false);

echo "✅ Waiting for messages...\n";

// ✅ MariaDB connection
$dsn = 'mysql:host=localhost;dbname=cakephpdb;charset=utf8mb4';
$dbUser = 'cakeuser';
$dbPass = '123456'; // যদি password থাকে, দিন

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    echo "✅ Connected to database\n";
} catch (PDOException $e) {
    die("❌ DB connection failed: " . $e->getMessage() . "\n");
}

// ✅ Message handler
$callback = function ($msg) use ($pdo) {
    echo 'Received message: ', $msg->body, "\n";

    $data = json_decode($msg->body, true);

    if ($data && isset($data['name']) && isset($data['email'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO logins (name, email) VALUES (:name, :email)");
            $stmt->execute([
                ':name' => $data['name'],
                ':email' => $data['email']
            ]);
            echo "✅ Data inserted into DB\n";
        } catch (PDOException $e) {
            echo "❌ DB insert error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ Invalid message format\n";
    }
};

// ✅ Start consuming
$channel->basic_consume('data_queue', '', false, true, false, false, $callback);

// ✅ Loop
while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$conn->close();
