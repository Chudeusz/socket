<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MobileSockets\Communicator;
use React\EventLoop\Factory;
use React\Socket\SecureServer;
use React\Socket\Server;

require __DIR__ . '/vendor/autoload.php';


$loop   = Factory::create();
$webSock = new SecureServer(
    new Server('0.0.0.0:8787', $loop),
    $loop,
    array(
        'local_cert'        => '/etc/letsencrypt/live/wss.owncp.pl/fullchain.pem', // path to your cert bpflow.pl.pem
        'local_pk'          => '/etc/letsencrypt/live/wss.owncp.pl/privkey.pem', // path to your server private key bpflow.pl.pem
        'allow_self_signed' => TRUE, // Allow self signed certs (should be false in production)
        'verify_peer' => FALSE
    )
);

//$webSock = new Server('0.0.0.0:8787', $loop);

$webServer = new IoServer(
    new HttpServer(
        new WsServer(
            new Communicator()
        )
    ),
    $webSock
);
$loop->run();

