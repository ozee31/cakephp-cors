# cakephp-cors

[![Build Status](https://travis-ci.org/ozee31/cakephp-cors.svg?branch=master)](https://travis-ci.org/ozee31/cakephp-cors)

A CakePHP (3.3+) plugin for activate cors domain in your application with [Middleware](http://book.cakephp.org/3.0/en/controllers/middleware.html).

[Learn more about CORS](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS)

## Requirements

- PHP version 5.6 or higher
- CakePhp 3.3 or higher

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require ozee31/cakephp-cors
```

## Quick Start

Loading the Plugin

```PHP
  // In config/bootstrap.php
  Plugin::load('Cors', ['bootstrap' => true, 'routes' => false]);
```

By default the plugin authorize cors for all origins, all methods and all headers and caches all for one day.

## Configuration

### Default configuration

```PHP
<?php
[
    'AllowOrigin' => true, // accept all origin
    'AllowCredentials' => true,
    'AllowMethods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], // accept all HTTP methods
    'AllowHeaders' => true, // accept all headers
    'ExposeHeaders' => false, // don't accept personal headers
    'MaxAge' => 86400, // cache for 1 day
    'exceptionRenderer' => 'Cors\Error\AppExceptionRenderer', // Use ExeptionRenderer class of plugin
```

### Change config

In `app.php` add :

```PHP
'Cors' => [
    // My Config
]
```

#### AllowOrigin ([Access-Control-Allow-Origin](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS#Access-Control-Allow-Origin))

A returned resource may have one Access-Control-Allow-Origin header, with the following syntax:

```PHP
'Cors' => [
    // Accept all origins
    'AllowOrigin' => true,
    // OR
    'AllowOrigin' => '*',

    // Accept one origin
    'AllowOrigin' => 'http://flavienbeninca.fr'

    // Accept many origins
    'AllowOrigin' => ['http://flavienbeninca.fr', 'http://google.com']
]
```

#### AllowCredentials ([Access-Control-Allow-Credentials](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS#Access-Control-Allow-Credentials))

The Access-Control-Allow-Credentials header Indicates whether or not the response to the request can be exposed when the credentials flag is true.  When used as part of a response to a preflight request, this indicates whether or not the actual request can be made using credentials. Note that simple GET requests are not preflighted, and so if a request is made for a resource with credentials, if this header is not returned with the resource, the response is ignored by the browser and not returned to web content.

```PHP
'Cors' => [
    'AllowCredentials' => true,
    // OR
    'AllowCredentials' => false,
]
```

#### AllowMethods ([Access-Control-Allow-Methods](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS#Access-Control-Allow-Methods))

```PHP
'Cors' => [
    // string
    'AllowMethods' => 'POST',
    // OR array
    'AllowMethods' => ['GET', 'POST'],
]
```

#### AllowHeaders ([Access-Control-Allow-Headers](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS#Access-Control-Allow-Headers))

The Access-Control-Allow-Headers header is used in response to a preflight request to indicate which HTTP headers can be used when making the actual request.

```PHP
'Cors' => [
    // accept all headers
    'AllowHeaders' => true,

    // accept just authorization
    'AllowHeaders' => 'authorization',

    // accept many headers
    'AllowHeaders' => ['authorization', 'other-header'],
]
```

#### ExposeHeaders ([Access-Control-Expose-Headers](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS#Access-Control-Expose-Headers))

The Access-Control-Expose-Headers header lets a server whitelist headers that browsers are allowed to access. For example:

```PHP
'Cors' => [
    // nothing
    'ExposeHeaders' => false,

    // string
    'ExposeHeaders' => 'X-My-Custom-Header',

    // array
    'ExposeHeaders' => ['X-My-Custom-Header', 'X-Another-Custom-Header'],
]
```

#### MaxAge ([Access-Control-Max-Age](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS#Access-Control-Max-Age))

The  Access-Control-Max-Age header indicates how long the results of a preflight request can be cached. For an example of a preflight request, see the above examples.

```PHP
'Cors' => [
    // no cache
    'MaxAge' => false,

    // 1 hour
    'MaxAge' => 3600,

    // 1 day
    'MaxAge' => 86400,
]
```

#### exceptionRenderer

This option overload default `exceptionRenderer` in `app.php`.

By default this class extends from `Error.exceptionRenderer` to add Cors Headers

If you don't want to overload exceptionRenderer, You must write

```PHP
'Cors' => [
	'exceptionRenderer' => false
]
```

[Read more](http://book.cakephp.org/3.0/en/development/errors.html#extend-the-baseerrorhandler)
