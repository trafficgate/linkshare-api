<?php

namespace Linkshare\Api;

use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Grant\ScopedPassword;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Linkshare as LinkshareProvider;
use League\OAuth2\Client\Token\AccessToken;
use Linkshare\Exceptions\LinkshareApiAuthorizationException;
use Linkshare\Exceptions\MissingFieldException;
use Linkshare\Exceptions\ResourceUnavailableException;
use SimpleXMLElement;

abstract class AbstractLinkshareApi extends AbstractApi
{
    const BASE_API_URL = 'https://api.rakutenmarketing.com/{name}/{version}';
    const API_NAME     = '';
    const API_VERSION  = '';

    /**
     * The username.
     *
     * @var string
     */
    protected $username;

    /**
     * The password.
     *
     * @var string
     */
    protected $password;

    /**
     * The scope.
     *
     * @var string
     */
    protected $scope;

    /**
     * AbstractLinkshareApi constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        parent::__construct($options);

        if (isset($options['username']) && $options['username'] !== false) {
            $this->setUsername($options['username']);
        }

        if (isset($options['password']) && $options['password'] !== false) {
            $this->setPassword($options['password']);
        }

        if (isset($options['scope']) && $options['scope'] !== false) {
            $this->setScope($options['scope']);
        }

        $this->data = [];
    }

    /**
     * Set the username.
     *
     * @param string $username
     */
    protected function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Set the password.
     *
     * @param string $password
     */
    protected function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Set the scope.
     *
     * @param string $scope
     */
    protected function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * Determine whether the user credentials were provided or not.
     *
     * @return bool
     */
    protected function haveUserCredentials()
    {
        if (isset($this->username, $this->password, $this->sid)) {
            return true;
        }

        return false;
    }

    /**
     * Get the OAuth provider.
     *
     * @param array $options
     * @return AbstractProvider
     */
    public function getProvider(array $options = [])
    {
        return new LinkshareProvider([
            'clientId'     => $this->clientId,
            'clientSecret' => $this->clientSecret,
            'timeout'      => $this->timeout,
        ]);
    }

    /**
     * Get the base API url.
     *
     * Use '{name}' and '{version}' to specify where the api name and version should be inserted.
     *
     * @return string
     */
    public function getBaseApiUrl()
    {
        return static::BASE_API_URL;
    }

    /**
     * Get the API name.
     *
     * @return string
     */
    public function getApiName()
    {
        return static::API_NAME;
    }

    /**
     * Get the API version.
     *
     * @return string
     */
    public function getApiVersion()
    {
        return static::API_VERSION;
    }

    /**
     * Get the access token.
     *
     * Use ScopedPassword as the default grant.
     *
     * @see AbstractApi::getAccessToken()
     * @param AbstractGrant|null $grant
     * @param array $options
     * @return AccessToken
     * @throws MissingFieldException
     */
    public function getAccessToken(AbstractGrant $grant = null, array $options = [])
    {
        if ($grant === null) {
            $grant = new ScopedPassword;
        }

        if ($options === []) {
            $options = [
                'username' => $this->username,
                'password' => $this->password,
                'scope'    => $this->scope,
            ];
        }

        return parent::getAccessToken($grant, $options);
    }

    /**
     * Attempt to access the API and return the response.
     *
     * Throws an exception if something goes wrong.
     *
     * @param string $method
     * @param array $options
     * @return array|string
     * @throws LinkshareApiAuthorizationException
     * @throws ResourceUnavailableException
     */
    public function get($method = 'GET', array $options = [])
    {
        $result = parent::get($method, $options);

        // Check if the first character of the result is '{' to assume it is JSON
        if (is_array($result)) {
            // In the case of JSON, there is a possibility that the resource in unavailable
            if (isset($result['fault'])) {
                throw new ResourceUnavailableException($result);
            }
        }

        // Check if the first character of the result is '<' to assume it is XML
        if (is_string($result) && strpos($result, '<') === 0) {
            // Try to load the XML assuming an authorization problem
            $xml = new SimpleXMLElement($result, $options = 0, $dataIsUrl = false, $ns = 'ams', $isPrefix = true);

            // If we have a code, message, and description, it means we had a problem in authorization
            if (isset($xml->code, $xml->message, $xml->description)) {
                throw new LinkshareApiAuthorizationException($xml);
            }
        }

        return $result;
    }

    /**
     * Return the API values in the form required for making an API call.
     *
     * @return string
     */
    protected function getUrlQuery()
    {
        return http_build_query($this->getData());
    }

    /**
     * Set a value to the given URL parameter or remove it from the array if null.
     *
     * @param $parameter
     * @param $value
     * @return $this
     */
    protected function setParameter($parameter, $value)
    {
        if ($value === null) {
            if (is_string($parameter)) {
                unset($this->data[$parameter]);
            } elseif (is_integer($parameter)) {
                $this->data[$parameter] = null;
            }
        } else {
            $this->data[$parameter] = $value;
        }

        return $this;
    }
}
