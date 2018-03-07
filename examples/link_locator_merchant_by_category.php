<?php

// Load vendor libraries
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/ExampleApiCaller.php';

use Linkshare\Api\LinkLocator;

$apiCaller = new ExampleApiCaller([
    'client_id'         => getenv('LINKSHARE_API_CLIENT_ID'),
    'client_secret'     => getenv('LINKSHARE_API_CLIENT_SECRET'),
    'username'          => getenv('LINKSHARE_API_USERNAME'),
    'password'          => getenv('LINKSHARE_API_PASSWORD'),
    'scope'             => getenv('LINKSHARE_API_SCOPE'),
    'access_token_path' => __DIR__.'/access_token',
    'debug'             => getenv('LINKSHARE_API_DEBUG'),
]);

$merchantId = getenv('EXAMPLE_CATEGORY_ID');

// Create product search client
$linkLocator = new LinkLocator([
    'client_id'     => $apiCaller->clientId,
    'client_secret' => $apiCaller->clientSecret,
    'username'      => $apiCaller->username,
    'password'      => $apiCaller->password,
    'scope'         => $apiCaller->scope,
    'access_token'  => $apiCaller->accessToken,
]);

$apiCaller->makeRequest(
    $linkLocator,
    function (LinkLocator $provider) use ($categoryId) {
        $provider->merchantByCategory();
    }
);
