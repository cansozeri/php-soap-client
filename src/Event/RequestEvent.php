<?php

namespace Canszr\SoapClient\Event;

use Canszr\SoapClient\EngineDto;

class RequestEvent
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
     * @var array
     */
    protected $arguments;

    public function __construct(EngineDto $engine, string $method, array $arguments)
    {
        $this->engine = $engine;
        $this->method = $method;
        $this->arguments = $arguments;
    }

    public function getEngine(): EngineDto
    {
        return $this->engine;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }
}