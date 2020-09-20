<?php

namespace Canszr\SoapClient\Event;

use Canszr\SoapClient\EngineDto;
use Canszr\SoapClient\Type\ResultInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseEvent
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var EngineDto
     */
    protected $engine;

    /**
     * @var mixed
     */
    protected $response;

    public function __construct(EngineDto $engine, string $method, ResultInterface $response)
    {
        $this->engine = $engine;
        $this->method = $method;
        $this->response = $response;
    }

    public function getEngine(): EngineDto
    {
        return $this->engine;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}