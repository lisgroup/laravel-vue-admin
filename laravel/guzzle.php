<?php


require_once __DIR__.'/vendor/autoload.php';


use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

$client = new Client();

$requests = function($total) {
    $uri = 'http://127.0.0.1/index.php';
    for ($i = 0; $i < $total; $i++) {
        yield new Request('GET', $uri);
    }
};

$pool = new Pool($client, $requests(5), [
    'concurrency' => 1,
    'fulfilled' => function($response, $index) {
        // this is delivered each successful response
        echo $index.PHP_EOL;
        var_dump($response->getBody()->getContents());
    },
    'rejected' => function($reason, $index) {
        // this is delivered each failed request
        var_dump($reason);
    },
]);

// Initiate the transfers and create a promise
$promise = $pool->promise();

// Force the pool of requests to complete.
$promise->wait();

echo 'finish';
