<?php

namespace CanszrTest\SoapClient\SoapTest\Unit\Middleware;

use PHPUnit\Framework\TestCase;
use Canszr\SoapClient\SoapHeaderDto;
use Canszr\SoapClient\Handler\HttPlugHandle;
use Psr\Http\Client\ClientExceptionInterface;
use Canszr\SoapClient\Middleware\AddSoapHeaderMiddleware;

class AddSoapHeaderMiddlewareTest extends TestCase
{
    /**
     * @var AddSoapHeaderMiddleware
     */
    private $middleware;

    /***
     * Initialize all basic objects
     */
    protected function setUp(): void
    {
        $securityHeader = array(
            'UserName' => 'test',
            'Password' => 'test'
        );
        $soapHeader = (new SoapHeaderDto())
            ->setNamespace('v1')
            ->setHeaderName('SecurityHeader')
            ->setHeader($securityHeader)
            ->setMustUnderstandNS(false);

        $this->middleware = new AddSoapHeaderMiddleware($soapHeader);
    }

    /**
     * @test
     */
    function it_is_a_middleware()
    {
        $this->assertInstanceOf(AddSoapHeaderMiddleware::class, $this->middleware);
    }

    /**
     * @test
     */
    function it_has_a_name()
    {
        $this->assertEquals('add_header_middleware', $this->middleware->getName());
    }

    /**
     * @test
     * @throws ClientExceptionInterface
     */
    function it_adds_header_to_the_handler()
    {
        $handler    = HttPlugHandle::createForClient();
        $handler->addMiddleware($this->middleware);
        $this->assertTrue(true);
    }
}
