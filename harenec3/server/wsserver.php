<?php
use Workerman\Worker;
use Workerman\Connection\TcpConnection;

require_once '../vendor/autoload.php';

$ws_worker = new Worker("websocket://0.0.0.0:8282");
$ws_worker->count = 1; // 1 proces

$game = new Game(1000, 500);

$ws_worker->onConnect = function($connection) use (&$players, &$game){
    $uuid = uniqid();
    $game->addPlayer(new Player($game, $uuid));
    $connection->playerID = $uuid;
    $connection->send(json_encode(["uuid" => $uuid]));
    echo "Connected player with UUID:" . $uuid;
    var_dump($players);
};

// receiving data from the client
$ws_worker->onMessage = function(TcpConnection $connection, $data) use ($ws_worker, &$game) {
    $dataRcv = json_decode($data, true);
    if ($data->type === "playerPosition") {
        $game->update($dataRcv->payload->x, $dataRcv->payload->x);
        
    }
    foreach ($ws_worker->connections as $conn) {
        $conn->send($data);
    }
};


$ws_worker->onClose = function($connection) use (&$players){
    
    foreach ($players as $uuid => $playerConnection) {
        if ($playerConnection === $connection->playerID) {
            unset($players[$uuid]);  // Remove disconnected player from the array
            echo "Player with UUID: $connection->playerID disconnected\n";  // Log disconnection message
            break; // Exit the loop once the disconnected player is found
        }
    }
    var_dump($players);
    
    echo "connection closed\n";
};

// Run the worker
Worker::runAll();