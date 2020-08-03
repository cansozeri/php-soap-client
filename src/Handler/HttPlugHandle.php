<?php

namespace Canszr\SoapClient\Handler;

use Canszr\SoapClient\HttpBinding\Converter\Psr7Converter;
use Canszr\SoapClient\HttpBinding\LastRequestInfo;
use Canszr\SoapClient\HttpBinding\SoapRequest;
use Canszr\SoapClient\HttpBinding\SoapResponse;
use Canszr\SoapClient\Middleware\CollectLastRequestInfoMiddleware;
use Canszr\SoapClient\Middleware\MiddlewareInterface;
use Canszr\SoapClient\Middleware\MiddlewareSupportingInterface;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Client\ClientExceptionInterface;

class HttPlugHandle implements HandlerInterface, MiddlewareSupportingInterface
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var LastRequestInfoCollectorInterface
     */
    private $lastRequestInfoCollector;

    /**
     * @var Psr7Converter
     */
    private $converter;

    /**
     * @var array
     */
    private $middlewares = [];

    public function __construct(
        HttpClient $client,
        Psr7Converter $converter,
        CollectLastRequestInfoMiddleware $lastRequestInfoCollector
    ) {
        $this->client = $client;
        $this->converter = $converter;
        $this->lastRequestInfoCollector = $lastRequestInfoCollector;
    }

    public static function createWithDefaultClient(): HttPlugHandle
    {
        return self::createForClient(HttpClientDiscovery::find());
    }

    public static function createForClient(HttpClient $client = null): HttPlugHandle
    {
        return new self(
            $client ?: HttpClientDiscovery::find(),
            new Psr7Converter(
                Psr17FactoryDiscovery::findRequestFactory(),
                Psr17FactoryDiscovery::findStreamFactory()
            ),
            new CollectLastRequestInfoMiddleware()
        );
    }

    public function addMiddleware(MiddlewareInterface $middleware)
    {
        $this->middlewares[$middleware->getName()] = $middleware;
    }

    /**
     * @param SoapRequest $request
     * @return SoapResponse
     * @throws \Exception
     */
    public function request(SoapRequest $request): SoapResponse
    {
        $client = new PluginClient(
            $this->client,
            array_merge(
                array_values($this->middlewares),
                [$this->lastRequestInfoCollector]
            )
        );

        $psr7Request = $this->converter->convertSoapRequest($request);
        try {
            $psr7Response = $client->sendRequest($psr7Request);
        } catch (ClientExceptionInterface $e) {
            throw new \Exception($e->getMessage());
        }

        return $this->converter->convertSoapResponse($psr7Response);
    }

    public function collectLastRequestInfo(): LastRequestInfo
    {
        return $this->lastRequestInfoCollector->collectLastRequestInfo();
    }
}