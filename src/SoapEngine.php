<?php

namespace Canszr\SoapClient;

use Canszr\SoapClient\Handler\HandlerInterface;
use Canszr\SoapClient\Handler\SoapClientHandler;
use Canszr\SoapClient\Service\SoapService;
use Canszr\SoapClient\Service\SoapServiceInterface;

trait SoapEngine
{
    /**
     * @var SoapServiceInterface
     */
    private $service;

    /**
     * @var HandlerInterface
     */
    private $handler;

    /**
     * @var SoapOptions
     */
    private $options;

    protected function setService(SoapServiceInterface $soapService)
    {
        $this->service = $soapService;
    }

    protected function getService(): SoapServiceInterface
    {
        return $this->service;
    }

    protected function setHandler(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    protected function getHandler(): HandlerInterface
    {
        return $this->handler;
    }

    /**
     * @param SoapOptions $options
     * @return SoapService
     * @throws \Exception
     */
    public function fromOptions(SoapOptions $options): SoapServiceInterface
    {
        $driver = SoapDriver::createFromOptions($options);
        $this->setHandler(new SoapClientHandler($driver->getClient()));
        $this->setService(new SoapService($driver, $this->getHandler()));

        return $this->getService();
    }

    /**
     * @param SoapOptions $options
     * @param HandlerInterface $handler
     * @return SoapService
     * @throws \Exception
     */
    public function fromOptionsWithHandler(SoapOptions $options, HandlerInterface $handler): SoapServiceInterface
    {
        $driver = SoapDriver::createFromOptions($options);
        $this->setHandler($handler);
        $this->setService(new SoapService($driver, $this->getHandler()));

        return $this->getService();
    }
}