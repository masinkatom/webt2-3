<?php
use Workerman\Worker;
use Workerman\Connection\TcpConnection;
use Workerman\Timer;

require_once '../vendor/autoload.php';
require_once 'game/Game.php';
require_once 'game/Player.php';

$ws_worker = new Worker("websocket://0.0.0.0:8282");
$ws_worker->count = 1; // 1 proces

$game = new Game(1000, 500, 1);

$ws_worker->onConnect = function($connection) use (&$game, $ws_worker){
    $uuid = uniqid();
    $game->addPlayer(new Player($game, $uuid));
    $connection->playerID = $uuid;
    $connection->send(prepareData("connectedPlayer", $uuid));
    $connectionAmount = count($ws_worker->connections);
    Timer::delAll();
    $connection->timerID = Timer::add($game->getRefreshTimeSeconds(), 'sendDataToAll', array($ws_worker, "playerPosition", $game->getPlayers()));
    echo "Connected player with UUID:" . $uuid . "\n";
    var_dump(json_encode($game->getPlayers()));
    
};

// receiving data from the client
$ws_worker->onMessage = function(TcpConnection $connection, $data) use (&$game) {
    $dataRcv = json_decode($data, true);
    if ($dataRcv["type"] === "playerPosition") {
        foreach ($game->getPlayers() as $player) {
            if (isset($connection->playerID) && $player->getUuid() == $connection->playerID) {
                $player->update($dataRcv["payload"]["x"], $dataRcv["payload"]["y"]);
            }
        }
    }
};


$ws_worker->onClose = function($connection) use (&$game, $ws_worker){
    $game->removePlayer($connection->playerID);  // Remove disconnected player from the array

    Timer::delAll();

    foreach ($ws_worker->connections as $conn) {
        if ($connection->playerID != $conn->playerID) {
            $conn->timerID = Timer::add($game->getRefreshTimeSeconds(), 'sendDataToAll', array($ws_worker, "playerPosition", $game->getPlayers()));
            break;
        }
    }
    echo "\nconnection closed\n";
    var_dump("after closed - " . json_encode($game->getPlayers()));
};

function prepareData($type, $data) {
    $toSend = [
        "type" => $type,
        "payload" => $data
    ];
    return json_encode($toSend);
}

function sendDataToAll($ws_worker, $type, $data) {
    // echo "\n";
    $toSend = prepareData($type, $data);
    var_dump($toSend);
    //echo "\n" . $toSend;

    foreach ($ws_worker->connections as $conn) {
        //$conn->send($data);
        $conn->send($toSend);
    }
}

// Run the worker
Worker::runAll();