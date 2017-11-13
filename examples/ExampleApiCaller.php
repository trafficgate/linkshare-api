<?php

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Linkshare\Api\AbstractApi;

class ExampleApiCaller
{
    public $accessToken;
    public $clientId;
    public $clientSecret;
    public $debug;
    public $password;
    public $requestsPerMinute;
    public $scope;
    public $username;

    public function __construct(array $options = [])
    {
        // Set for showing debug output
        $this->debug             = isset($options['debug']) ? $options['debug'] : false;

        // Set user values via environment variables
        $this->clientId          = $options['client_id'];
        $this->clientSecret      = $options['client_secret'];
        $this->username          = $options['username'];
        $this->password          = $options['password'];
        $this->scope             = $options['scope'];
        $this->accessTokenPath   = $options['access_token_path'];

        // Set # of requests per minute
        $this->requestsPerMinute = isset($options['requests_per_minute']) ? $options['requests_per_minute'] : 5;

        // Get access token from file if it exists
        $this->accessToken       = $this->loadAccessToken($this->accessTokenPath);
    }

    /**
     * Make an API request and optionally wait before the next call.
     *
     * @param AbstractApi $provider
     * @param callable    $callable
     * @param string      $accessTokenPath
     * @param int         $requestsPerMinute
     *
     * @throws Exception
     */
    public function makeRequest(AbstractApi $provider, callable $callable)
    {
        $waitTime = 0;

        if ($this->requestsPerMinute > 0) {
            $waitTime = $this->getWaitTime($this->requestsPerMinute);
        }

        // Prep the response
        $callable($provider);

        if ($this->debug) {
            printf('%-23s %-s'.PHP_EOL, 'API URL:', $provider->getApiUrl());
            echo PHP_EOL;
        }

        // Save the access token to file
        if (isset($this->accessTokenPath)) {
            $this->accessToken = $provider->getAccessToken();
            if ($this->accessToken->hasExpired()) {
                $this->saveAccessToken($provider->refreshToken(), $this->accessTokenPath);
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
        if ($this->requestsPerMinute > 0) {
            sleep($waitTime);
        }
    }

    /**
     * Load access token from path.
     *
     * @param string $path
     *
     * @return AccessToken|null
     */
    protected function loadAccessToken($path)
    {
        $this->accessToken = null;
        if (file_exists($path)) {
            $this->accessToken = unserialize(file_get_contents($path));

            if ($this->debug) {
                printf('%-23s %-s'.PHP_EOL, 'Stored access token:', serialize($this->accessToken));
            }
        }

        return $this->accessToken;
    }

    /**
     * Save access token to path.
     *
     * @param AccessToken $accessToken
     * @param string      $path
     */
    protected function saveAccessToken(AccessToken $accessToken, $path)
    {
        if ($this->debug) {
            printf('%-23s %-s'.PHP_EOL, 'Generated access token:', serialize($accessToken));
        }

        file_put_contents($path, serialize($accessToken));
    }

    /**
     * Determine the wait time between API calls.
     *
     * @param int $requestsPerMinute
     *
     * @return float
     */
    protected function getWaitTime($requestsPerMinute)
    {
        $waitTime = ceil(60 / $requestsPerMinute);

        if ($this->debug) {
            echo "Making one call every $waitTime second(s) to obey the limit of $requestsPerMinute requests per minute.".PHP_EOL;
        }

        return $waitTime;
    }
}
