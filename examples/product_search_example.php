<?php

// Load vendor libraries
require_once __DIR__.'/../vendor/autoload.php';

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Linkshare\Api\AbstractApi;
use Linkshare\Api\ProductSearch;

// Set global constant for showing debug output
define('DEBUG', true);

// Set # of requests per minute
$requestsPerMinute = 5;

// Set user values via environment variables
$clientId        = getenv('LINKSHARE_API_CLIENT_ID');
$clientSecret    = getenv('LINKSHARE_API_CLIENT_SECRET');
$username        = getenv('LINKSHARE_API_USERNAME');
$password        = getenv('LINKSHARE_API_PASSWORD');
$scope           = getenv('LINKSHARE_API_SCOPE');
$accessTokenPath = __DIR__.'/access_token';

// Get access token from file if it exists
$accessToken = loadAccessToken($accessTokenPath);

// Create product search client
$productSearch = new ProductSearch([
    'client_id'     => isset($clientId) ? $clientId : null,
    'client_secret' => isset($clientSecret) ? $clientSecret : null,
    'username'      => isset($username) ? $username : null,
    'password'      => isset($password) ? $password : null,
    'scope'         => isset($scope) ? $scope : null,
    'access_token'  => isset($accessToken) ? $accessToken : null,
]);

makeRequest(
    $productSearch,
    function (ProductSearch $provider) {
        $provider
            ->keyword('dog beer', ProductSearch::API_PARAM_KEYWORD)
            ->category('dog')
            ->maximumResults(1)
            ->pageNumber(1)
            ->merchantId(1)
            ->sort(ProductSearch::SORT_COLUMN_RETAIL_PRICE, ProductSearch::SORT_TYPE_ASC);
    },
    $requestsPerMinute,
    $accessTokenPath
);

/**
 * Load access token from path.
 *
 * @param string $path
 * @return AccessToken|null
 */
function loadAccessToken($path)
{
    $accessToken = null;
    if (file_exists($path)) {
        $accessToken = unserialize(file_get_contents($path));

        if (DEBUG) {
            printf('%-23s %-s'.PHP_EOL, 'Stored access token:', serialize($accessToken));
        }
    }

    return $accessToken;
}

/**
 * Save access token to path.
 *
 * @param AccessToken $accessToken
 * @param string $path
 */
function saveAccessToken(AccessToken $accessToken, $path)
{
    if (DEBUG) {
        printf('%-23s %-s'.PHP_EOL, 'Generated access token:', serialize($accessToken));
    }

    file_put_contents($path, serialize($accessToken));
}

/**
 * Make an API request and optionally wait before the next call.
 *
 * @param AbstractApi $provider
 * @param Callable $callable
 * @param string $accessTokenPath
 * @param int $requestsPerMinute
 * @throws Exception
 */
function makeRequest(AbstractApi $provider, callable $callable, $requestsPerMinute = 0, $accessTokenPath = 'access_token')
{
    $waitTime = 0;

    if ($requestsPerMinute > 0) {
        $waitTime = getWaitTime($requestsPerMinute);
    }

    // Prep the response
    $callable($provider);

    if (DEBUG) {
        printf('%-23s %-s'.PHP_EOL, 'API URL:', $provider->getApiUrl());
        echo PHP_EOL;
    }

    // Save the access token to file
    if (isset($accessTokenPath)) {
        $accessToken = $provider->getAccessToken();
        if ($accessToken->hasExpired()) {
            saveAccessToken($provider->refreshToken(), $accessTokenPath);
        }
    }

    try {
        $result = $provider->get();
        echo $result;
    } catch (IdentityProviderException $e) {
        var_export($e);
    }
    echo PHP_EOL;

    // Determine wait time between calls
    if ($requestsPerMinute > 0) {
        sleep($waitTime);
    }
}

/**
 * Determine the wait time between API calls.
 *
 * @param int $requestsPerMinute
 * @return float
 */
function getWaitTime($requestsPerMinute)
{
    $waitTime = ceil(60 / $requestsPerMinute);

    if (DEBUG) {
        echo "Making one call every $waitTime second(s) to obey the limit of $requestsPerMinute requests per minute.".PHP_EOL;
    }

    return $waitTime;
}
