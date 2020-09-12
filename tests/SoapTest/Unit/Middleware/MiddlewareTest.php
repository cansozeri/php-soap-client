<?php

namespace CanszrTest\SoapClient\SoapTest\Unit\Middleware;

use Canszr\SoapClient\Middleware\Middleware;
use Canszr\SoapClient\Middleware\MiddlewareInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Client\Common\PluginClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

class MiddlewareTest extends TestCase
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
     * @var Middleware
     */
    private $middleware;

    /***
     * Initialize all basic objects
     */
    protected function setUp(): void
    {
        $this->middleware = new Middleware();
        $this->mockClient = new Client(new GuzzleMessageFactory());
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
        $this->assertEquals('empty_middleware', $this->middleware->getName());
    }

    /**
     * @test
     * @throws ClientExceptionInterface
     */
    function it_applies_middleware_callbacks()
    {
        $this->mockClient->addResponse($response = new Response());
        $receivedResponse = $this->client->sendRequest($request = new Request('POST', '/', ['User-Agent' => 'no']));

        $sentRequest = $this->mockClient->getRequests()[0];
        $this->assertEquals($request, $sentRequest);
        $this->assertEquals($response, $receivedResponse);
    }
}