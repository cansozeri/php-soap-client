<?php

namespace Canszr\SoapClient;

use Canszr\SoapClient\Handler\HandlerInterface;
use Canszr\SoapClient\Service\SoapServiceInterface;

class EngineDto
{
    /**
     * @var SoapServiceInterface
     */
    private $service;

    /**
     * @var HandlerInterface
     */
    private $handler;

    public function setService(SoapServiceInterface $soapService)
    {
        $this->service = $soapService;
    }

    public function getService(): SoapServiceInterface
    {
        return $this->service;
    }

    public function setHandler(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    public function getHandler(): HandlerInterface
    {
        return $this->handler;
    }
}