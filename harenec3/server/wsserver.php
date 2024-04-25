<?php
use Workerman\Worker;
use Workerman\Connection\TcpConnection;
use Workerman\Timer;

require_once '../vendor/autoload.php';
require_once 'game/Game.php';
require_once 'game/Player.php';
require_once 'game/Line.php';

$ws_worker = new Worker("websocket://0.0.0.0:8282");
$ws_worker->count = 1; // 1 proces

$tickrate = 32;

$game = new Game(1000, 500, $tickrate);

// $serverLikeConnection;

$ws_worker->onConnect = function($connection) use (&$game, $ws_worker){
    $uuid = uniqid();
    $connection->playerID = $uuid;
    $connection->closed = false;

    // if someone has connected I send him data of other players on server
    sendDataToAll($ws_worker, "connectedPlayer", $uuid);
    $connection->send(prepareData("enemiesOnServer", $game->getPlayers()));
    // $serverLikeConnection = $connection;
    $game->addPlayer(new Player($game, $uuid));
    sendDataToAll($ws_worker, "gameState", $game->getPlayers());

    // we add a timer to most recent player on server, so game will work correctly
    // Timer::delAll();
    // $connection->timerID = Timer::add($game->getRefreshTimeSeconds(), 'sendDataToAll', array($ws_worker, "playerPosition", $game->getPlayersWLastLine()));
    
    echo "Connected player with UUID:" . $uuid . "\n";
    var_dump(json_encode($game->getPlayers()));
    
};

// receiving data from the client
$ws_worker->onMessage = function(TcpConnection $connection, $data) use (&$game, $ws_worker) {
    $dataRcv = json_decode($data, true);

    // if i get an update on mouse position update method is called on certain player
    if ($dataRcv["type"] === "playerPosition") {
        foreach ($game->getPlayers() as $player) {
            if (isset($connection->playerID) && $player->getUuid() == $connection->playerID) {
                $player->update($dataRcv["payload"]["x"], $dataRcv["payload"]["y"]);
                $lastLine = end($player->lines);
                // should be in game class
                foreach ($game->getPlayers() as $player2) {
                    if ($player2->getUuid() != $player->getUuid()) {
                        foreach ($player2->getLines() as $line) {
                            if (isIntersecting($lastLine->getX1(), $lastLine->getY1(), $lastLine->getX2(), $lastLine->getY2(), $line->getX1(), $line->getY1(), $line->getX2(), $line->getY2())) {
                                sendDataToAll($ws_worker, "endGame", $player->getUuid());
                                $connection->closed = true;
                                $connection->close();
                                break;
                            }
                        }
                        if ($connection->closed == true) {
                            break;
                        }
                    }
                }
            }
        }
    }
    if ($dataRcv["type"] === "message") {
        sendDataToAll($ws_worker, "message", [$dataRcv["sender"], $dataRcv["payload"]]);
    }
    // if ($connection->playerID == $serverLikeConnection->playerID) {
        sendDataToAll($ws_worker, "playerPosition", $game->getPlayersWLastLine());
    // }
     
};


$ws_worker->onClose = function($connection) use (&$game, $ws_worker){
    $game->removePlayer($connection->playerID);  // Remove disconnected player from the array

    // first we delete all player's timers, then we add it to an online one
    // Timer::delAll();

    // foreach ($ws_worker->connections as $conn) {
    //     if ($connection->playerID != $conn->playerID) {
    //         // $conn->timerID = Timer::add($game->getRefreshTimeSeconds(), 'sendDataToAll', array($ws_worker, "playerPosition", $game->getPlayers()));
    //         $serverLikeConnection = $conn;
    //         break;
    //     }
    // }
    sendDataToAll($ws_worker, "disconnectedPlayer", $connection->playerID);
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

function CCW($p1, $p2, $p3) {
    return ($p3['y'] - $p1['y']) * ($p2['x'] - $p1['x']) > ($p2['y'] - $p1['y']) * ($p3['x'] - $p1['x']);
}
function isIntersecting($x1, $y1, $x2, $y2, $x3, $y3, $x4, $y4) {

    return (CCW(['x' => $x1, 'y' => $y1], ['x' => $x3, 'y' => $y3], ['x' => $x4, 'y' => $y4]) != CCW(['x' => $x2, 'y' => $y2], ['x' => $x3, 'y' => $y3], ['x' => $x4, 'y' => $y4])) && (CCW(['x' => $x1, 'y' => $y1], ['x' => $x2, 'y' => $y2], ['x' => $x3, 'y' => $y3]) != CCW(['x' => $x1, 'y' => $y1], ['x' => $x2, 'y' => $y2], ['x' => $x4, 'y' => $y4]));
}

// Run the worker
Worker::runAll();