<?php

namespace Linkshare\Api;

use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;

interface ApiInterface
{
    /**
     * Get the OAuth provider.
     *
     * @param array $options
     * @return AbstractProvider
     */
    public function getProvider(array $options = []);

    /**
     * Get the base API url.
     *
     * Use '{name}' and '{version}' to specify where the api name and version should be inserted.
     *
     * @return string
     */
    public function getBaseApiUrl();

    /**
     * Get the API name.
     *
     * @return string
     */
    public function getApiName();

    /**
     * Get the API version.
     *
     * @return string
     */
    public function getApiVersion();

    /**
     * Get the API url.
     *
     * This should return the fully constructed url for the API request.
     * @return mixed
     */
    public function getApiUrl();

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
     */
    public function getAccessToken(AbstractGrant $grant = null, array $options = []);

    /**
     * Make the API call and return the result as an array.
     *
     * @return mixed
     */
    public function get();
}
