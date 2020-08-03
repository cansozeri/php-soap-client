<?php

declare(strict_types=1);

namespace CanszrTest\SoapClient\SoapTest\Integration\Soap\Service;

use Canszr\SoapClient\Handler\HandlerInterface;
use Canszr\SoapClient\Handler\SoapClientHandler;
use Canszr\SoapClient\Service\SoapService;
use Canszr\SoapClient\Service\SoapServiceInterface;
use Canszr\SoapClient\SoapDriver;
use Canszr\SoapClient\SoapOptions;

class SoapClientServiceTest extends AbstractServiceTest
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
        return true;
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

        $this->handler = new SoapClientHandler($driver->getClient());

        $this->service = new SoapService($driver, $this->handler);
    }
}