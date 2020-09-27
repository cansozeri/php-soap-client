# General purpose PHP SOAP-client
This package aims to help you with interpreting and binding SOAP 1.1 and SOAP 1.2 messages to PSR-7 HTTP messages.

<p align="center">
<a href="https://travis-ci.com/github/cansozeri/php-soap-client"><img src="https://travis-ci.com/cansozeri/php-soap-client.svg?branch=master"></a>
<a href="https://packagist.org/packages/cansozeri/php-soap-client"><img alt="GitHub release (latest by date)" src="https://img.shields.io/github/v/release/cansozeri/php-soap-client"></a>
<a href="https://packagist.org/packages/cansozeri/php-soap-client"><img alt="GitHub" src="https://img.shields.io/github/license/cansozeri/php-soap-client"></a>
</p>

## Installation
```sh
$ composer require cansozeri/php-soap-client
```
## Usage

#### SoapDriver
```
❗️ Make sure ext-soap is loaded.
```

This soap driver wraps PHPs ext-soap `\SoapClient` implementation.

- It abuses the `__doRequest()` method to make it possible to encode the request and decode the response.
- Metadata is being parsed based on the `__getTypes()` and `__getFunctions()` method.

**Example usage**

* You can use SoapEngine Trait to create a "Soap Service" Instance.
```php
<?php

use Canszr\SoapClient\SoapEngine;
use Canszr\SoapClient\SoapOptions;

use SoapEngine;

$options = SoapOptions::defaults($wsdl, [
            'soap_version' => SOAP_1_2,
        ])->disableWsdlCache();

$service = $this->fromOptions($options);
````

#### SoapOptions

This package provides a little wrapper around all available `\SoapClient` options.
We provide some default options and the additional options can be configured.
It will validate the options before they are passed to the `\SoapClient`.
This way, you'll spend less time browsing the official PHP documentation.

**Example usage**

```php
<?php

use Canszr\SoapClient\SoapOptions;

$options = SoapOptions::defaults($wsdl, ['soap_version' => SOAP_1_2])
    ->disableWsdlCache();

$typemap = $options->getTypeMap();
$typemap->add(new MyTypeConverter());
```

## Handlers

#### HttPlugHandle

*Features: LastRequestInfoCollector, MiddlewareSupporting*

[HTTPlug](http://httplug.io/) is a HTTP client abstraction that can be used with multiple client packages.
With this handler it is easy to get in control about the HTTP layer of the SOAP client.
You can specify one or multiple middlewares that are being applied on your http client.
This makes it possible to manipulate the request and response objects so that you can get full control.

This handler knows how to deal with HTTP middlewares if they are supported by your HTTP client.

**Dependencies**

Load HTTP plug core packages:

```sh
composer require psr/http-message:^1.0 php-http/httplug:^2.1 php-http/message-factory:^1.0 php-http/discovery:^1.7 php-http/message:^1.8 php-http/client-common:^2.1
```


**Select HTTP Client**

Select one of the many clients you want to use to perform the HTTP requests:
http://docs.php-http.org/en/latest/clients.html#clients-adapters

```sh
composer require php-http/client-implementation:^1.0
```

**Example usage**

```php
<?php

use Canszr\SoapClient\Handler\HttPlugHandle;
use Canszr\SoapClient\SoapEngine;
use Canszr\SoapClient\SoapOptions;
use Canszr\SoapClient\Middleware\BasicAuthMiddleware;
use Http\Adapter\Guzzle6\Client;


use SoapEngine;

$options = SoapOptions::defaults($wsdl, [
            'soap_version' => SOAP_1_2,
        ])->disableWsdlCache();

$handler = HttPlugHandle::createForClient(
    Client::createWithConfig(['headers' => ['User-Agent' => 'testing/1.0']])
);

$handler->addMiddleware(new BasicAuthMiddleware('user', 'password'));

$service = $this->fromOptionsWithHandler($options, $handler);
```
#### SoapClientHandle

*Features: LastRequestInfoCollector*

```
❗️ Make sure ext-soap is loaded.
```

The SoapClientHandle is used by default and works with the built-in `__doRequest()` method.
This Handle is not configurable and can be used for soap implementations which do not use extensions.
It is activated by default to get you going as quick as possible.


**Example usage**

```php
<?php

use Canszr\SoapClient\SoapEngine;
use Canszr\SoapClient\SoapOptions;

use SoapEngine;

$service = $this->fromOptions(SoapOptions::defaults($wsdl, []));
```