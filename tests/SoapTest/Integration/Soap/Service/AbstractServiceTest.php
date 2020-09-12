<?php

namespace CanszrTest\SoapClient\SoapTest\Integration\Soap\Service;

use Canszr\SoapClient\Handler\HandlerInterface;
use Canszr\SoapClient\Service\SoapServiceInterface;

abstract class AbstractServiceTest extends AbstractIntegrationTest
{
    abstract protected function getService(): SoapServiceInterface;

    abstract protected function getHandler(): HandlerInterface;

    /**
     * Some handlers don't return HTTP headers. This method can skip validation of the sent / received headers
     */
    abstract protected function skipLastHeadersCheck(): bool;

    /**
     * @test
     * @runInSeparateProcess
     */
    function it_should_know_the_last_request_and_response()
    {
        $this->configureForWsdl(FIXTURE_DIR . '/wsdl/calculator.wsdl');

        $handler = $this->getHandler();

        $lastInfo = $handler->collectLastRequestInfo();

        $this->assertEquals(0, strlen($lastInfo->getLastRequest()));
        $this->assertEquals(0, strlen($lastInfo->getLastResponse()));
        if (!$this->skipLastHeadersCheck()) {
            $this->assertEquals(0, strlen($lastInfo->getLastRequestHeaders()));
            $this->assertEquals(0, strlen($lastInfo->getLastResponseHeaders()));
        }

        $this->getService()->request('Multiply', [['intA' => 2, 'intB' => 2]]);

        $lastInfo = $handler->collectLastRequestInfo();
        $this->assertGreaterThan(0, strlen($lastInfo->getLastRequest()));
        $this->assertGreaterThan(0, strlen($lastInfo->getLastResponse()));

        if (!$this->skipLastHeadersCheck()) {
            $this->assertGreaterThan(0, strlen($lastInfo->getLastRequestHeaders()));
            $this->assertGreaterThan(0, strlen($lastInfo->getLastResponseHeaders()));
        }
    }
}