<?php

declare(strict_types=1);

namespace CanszrTest\SoapClient\SoapTest\Integration\Soap\Service;

use Canszr\SoapClient\Handler\HandlerInterface;
use Canszr\SoapClient\Handler\HttPlugHandle;
use Canszr\SoapClient\Service\SoapService;
use Canszr\SoapClient\Service\SoapServiceInterface;
use Canszr\SoapClient\SoapDriver;
use Canszr\SoapClient\SoapOptions;
use Http\Adapter\Guzzle6\Client;

class HttPlugServiceTest extends AbstractServiceTest
{
    /**
     * @var SoapServiceInterface
     */
    private $service;

    /**
     * @var HandlerInterface
     */
    private $handler;

    protected function getService(): SoapServiceInterface
    {
        return $this->service;
    }

    protected function getHandler(): HandlerInterface
    {
        return $this->handler;
    }

    protected function skipLastHeadersCheck(): bool
    {
        return false;
    }

    /**
     * @param string $wsdl
     * @throws \Exception
     */
    protected function configureForWsdl(string $wsdl)
    {
        try {
            $driver = SoapDriver::createFromOptions(
                SoapOptions::defaults($wsdl, [
                    'soap_version' => SOAP_1_2,
                ])->disableWsdlCache()
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->handler = HttPlugHandle::createForClient(
            Client::createWithConfig(['headers' => ['User-Agent' => 'testing/1.0']])
        );

        $this->service = new SoapService($driver, $this->handler);
    }
}