<?php

// Load vendor libraries
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/ExampleApiCaller.php';

use Carbon\Carbon;
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

$merchantId = getenv('EXAMPLE_MERCHANT_ID') ? getenv('EXAMPLE_MERCHANT_ID') : -1;
$categoryId = getenv('EXAMPLE_CATEGORY_ID') ? getenv('EXAMPLE_CATEGORY_ID') : -1;
$startDate  = getenv('EXAMPLE_START_DATE') ? Carbon::createFromFormat('mdY', getenv('EXAMPLE_START_DATE')) : null;
$endDate    = getenv('EXAMPLE_END_DATE') ? Carbon::createFromFormat('mdY', getenv('EXAMPLE_END_DATE')) : null;
$campaignId = getenv('EXAMPLE_CAMPAIGN_ID') ? getenv('EXAMPLE_CAMPAIGN_ID') : -1;
$page       = getenv('EXAMPLE_PAGE') ? getenv('EXAMPLE_PAGE') : 1;

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
    function (LinkLocator $provider) use ($merchantId, $categoryId, $startDate, $endDate, $campaignId, $page) {
        $provider->drmLinks($merchantId, $categoryId, $startDate, $endDate, $campaignId, $page);
    }
);
