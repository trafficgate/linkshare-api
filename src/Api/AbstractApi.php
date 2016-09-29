<?php

namespace Linkshare\Api;

use Exception;
use InvalidArgumentException;
use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Linkshare\Exceptions\MissingFieldException;

abstract class AbstractApi implements ApiInterface
{
    /**
     * The OAuth provider.
     *
     * @var AbstractProvider
     */
    protected $provider;

    /**
     * The client ID used for authorization.
     *
     * @var string
     */
    protected $clientId;

    /**
     * The client secret used for authorization.
     *
     * @var string
     */
    protected $clientSecret;

    /**
     * The access token for the API.
     *
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * Float describing the timeout of the request in seconds.
     * Use 0 to wait indefinitely (the default behavior).
     *
     * @var float
     */
    protected $timeout = 0;

    /**
     * The data for the query string.
     *
     * @var array
     */
    protected $data;

    public function __construct($options = [])
    {
        if (! isset($options['client_id']) || $options['client_id'] === false) {
            throw new MissingFieldException('client_id');
        }

        if (! isset($options['client_secret']) || $options['client_secret'] === false) {
            throw new MissingFieldException('client_secret');
        }

        if (isset($options['access_token']) && $options['access_token'] !== false) {
            $this->accessToken = $options['access_token'];
        }

        if (isset($options['timeout']) && ! is_numeric($options['timeout'])) {
            throw new InvalidArgumentException('Timeout must be numeric.');
        }

        $this->clientId     = $options['client_id'];
        $this->clientSecret = $options['client_secret'];
        $this->timeout      = $options['timeout'] ?? 0;

        $this->provider = $this->getProvider();
    }

    /**
     * Get the data.
     *
     * @return array
     */
    protected function getData()
    {
        return $this->data;
    }

    /**
     * Set the data.
     *
     * @param array $data
     */
    protected function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the access token.
     *
     * Determine if the access token is set or not. If it needs refreshing, refresh
     * it. If there's no access token, attempt to retrieve one from the server.
     * In the event of an error, throw an exception.
     *
     * @param AbstractGrant $grant
     * @param array $options
     * @return AccessToken
     * @throws Exception
     * @throws MissingFieldException
     */
    public function getAccessToken(AbstractGrant $grant = null, array $options = [])
    {
        if (! isset($this->accessToken)) {
            $this->accessToken = $this->provider->getAccessToken($grant, $options);
        }

        return $this->accessToken;
    }

    /**
     * Refresh the access token.
     *
     * @return AccessToken
     * @throws Exception
     */
    public function refreshToken()
    {
        if (! isset($this->accessToken)) {
            throw new Exception('Cannot refresh null access token.');
        }

        if ($this->accessToken->hasExpired()) {
            $this->accessToken = $this->provider->getAccessToken('refresh_token', [
                'refresh_token' => $this->accessToken->getRefreshToken(),
            ]);
        }

        return $this->accessToken;
    }

    /**
     * Reset the data to an empty array.
     */
    public function reset()
    {
        $this->data = [];
    }

    /**
     * Attempt to access the API and return the response.
     *
     * Throws an exception if something goes wrong.
     *
     * @param string $method
     * @param array $options
     * @return mixed
     * @throws MissingFieldException
     */
    public function get($method = 'GET', array $options = [])
    {
        $accessToken = $this->getAccessToken();

        // The provider provides a way to get an authenticated API request for
        // the service, using the access token; it returns an object conforming
        // to Psr\Http\Message\RequestInterface.
        $request  = $this->provider->getAuthenticatedRequest($method, $this->getApiUrl(), $accessToken, $options);
        $response = $this->provider->getResponse($request);

        return $response;
    }

    /**
     * Get the API url.
     *
     * This should return the fully constructed url for the API request.
     *
     * @return mixed
     */
    public function getApiUrl()
    {
        $url = $this->getBaseApiUrl();

        $url = str_replace('{name}', $this->getApiName(), $url);
        $url = str_replace('{version}', $this->getApiVersion(), $url);

        return $url;
    }
}
