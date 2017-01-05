# linkshare-api

[![Latest Version on Packagist](https://img.shields.io/packagist/v/linkshare/linkshare-api.svg?style=flat-square)](https://packagist.org/packages/linkshare/linkshare-api)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/trafficgate/linkshare-api/master.svg?style=flat-square)](https://travis-ci.org/trafficgate/linkshare-api)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/e776b67b-6cdd-49dd-8ea8-ffc0d0b68511.svg?style=flat-square)](https://insight.sensiolabs.com/projects/e776b67b-6cdd-49dd-8ea8-ffc0d0b68511)
[![StyleCI](https://styleci.io/repos/69539191/shield?branch=master)](https://styleci.io/repos/69539191)
[![Total Downloads](https://img.shields.io/packagist/dt/trafficgate/linkshare-api.svg?style=flat-square)](https://packagist.org/packages/trafficgate/linkshare-api)

API clients for consuming LinkShare developer APIs.

## What is this?

This package provides clients for LinkShare's developer APIs.

[LinkShare Developer's Portal](https://developers.rakutenmarketing.com/)

Implementation is based on [The PHP League's OAuth 2.0 client libraries](https://github.com/thephpleague/oauth2-client).

Currently implemented APIs:

* Link Locator (partial implementation)
* Product Search

## Usage

You can find examples of how to use the APIs under the examples folder.

Generally though, a new API client is created in the following fashion:

```php

// Options contain the information required for
// authenticating against the API endpoints.
$options = [
    // Required: The client ID as provided by the developer's portal
    // This will vary for each API.
    'client_id'     => $clientId,

    // Required: The client secret as provided by the developer's portal
    // This will vary for each API.
    'client_secret' => $password,

    // Required: The access token as provided by the developer's portal
    // This will vary for each API.
    'access_token'  => $access_token,

    // Required: Your LinkShare Affiliate username
    'username'      => $username,

    // Required: Your LinkShare Affiliate password
    'password'      => $password,

    // Required: Your LinkShare Affiliate Site ID
    'scope'         => $scope,

    // Optional: The timeout (in seconds) before a request fails
    'timeout'       => $timeout,
];

// To create an API client, simply supply the required options as shown above
$linkLocator   = new LinkLocator($options);
$productSearch = new ProductSearch($options);
```