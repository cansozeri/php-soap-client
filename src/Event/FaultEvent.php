<?php

namespace Canszr\SoapClient\Event;

use Canszr\SoapClient\EngineDto;
use Canszr\SoapClient\Exception\SoapException;

class FaultEvent
{
    /**
     * @var RequestEvent
     */
    protected $requestEvent;

    /**
     * @var EngineDto
     */
    protected $engine;

    /**
     * @var SoapException
     */
    protected $soapException;

    public function __construct(EngineDto $engine, SoapException $soapException, RequestEvent $requestEvent)
    {
        $this->engine = $engine;
        $this->requestEvent = $requestEvent;
        $this->soapException = $soapException;
    }

    public function getEngine(): EngineDto
    {
        return $this->engine;
    }

    public function getRequestEvent(): RequestEvent
    {
        return $this->requestEvent;
    }

    /**
     * @return SoapException
     */
    public function getSoapException(): SoapException
    {
        return $this->soapException;
    }
}