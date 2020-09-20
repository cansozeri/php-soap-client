<?php

namespace CanszrTest\SoapClient\SoapTest\Unit\Middleware;

use Canszr\SoapClient\Middleware\BasicAuthMiddleware;
use Canszr\SoapClient\Middleware\MiddlewareInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Http\Mock\Client;
use Psr\Http\Client\ClientExceptionInterface;

class BasicAuthMiddlewareTest extends TestCase
{
    /**
     * @var PluginClient
     */
    private $client;

    /**
     * @var Client
     */
    private $mockClient;

    /**
     * @var BasicAuthMiddleware
     */
    private $middleware;

    /***
     * Initialize all basic objects
     */
    protected function setUp(): void
    {
        $this->middleware = new BasicAuthMiddleware('username', 'password');
        $this->mockClient = new Client(Psr17FactoryDiscovery::findResponseFactory());
        $this->client = new PluginClient($this->mockClient, [$this->middleware]);
    }

    /**
     * @test
     */
    function it_is_a_middleware()
    {
        $this->assertInstanceOf(MiddlewareInterface::class, $this->middleware);
    }

    /**
     * @test
     */
    function it_has_a_name()
    {
        $this->assertEquals('basic_auth_middleware', $this->middleware->getName());
    }

    /**
     * @test
     * @throws ClientExceptionInterface
     */
    function it_adds_basic_auth_to_the_request()
    {
        $this->mockClient->addResponse(new Response());
        $this->client->sendRequest(new Request('POST', '/'));
        $sentRequest = $this->mockClient->getRequests()[0];
        $this->assertEquals(
            sprintf('Basic %s', base64_encode('username:password')),
            $sentRequest->getHeader('Authorization')[0]);
    }
}