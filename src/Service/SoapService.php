<?php

namespace Canszr\SoapClient\Service;

use Canszr\SoapClient\Handler\HandlerInterface;
use Canszr\SoapClient\HttpBinding\LastRequestInfo;
use Canszr\SoapClient\MetaData\MetadataInterface;

class SoapService implements SoapServiceInterface
{
    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @var HandlerInterface
     */
    private $handler;

    public function __construct(
        DriverInterface $driver,
        HandlerInterface $handler
    ) {
        $this->driver = $driver;
        $this->handler = $handler;
    }

    public function getMetadata(): MetadataInterface
    {
        return $this->driver->getMetadata();
    }

    public function request(string $method, array $arguments)
    {
        $request = $this->driver->encode($method, $arguments);
        $response = $this->handler->request($request);

        return $this->driver->decode($method, $response);
    }

    public function collectLastRequestInfo(): LastRequestInfo
    {
        return $this->handler->collectLastRequestInfo();
    }
}