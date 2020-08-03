<?php

namespace Canszr\SoapClient\Handler;

use Canszr\SoapClient\Client;
use Canszr\SoapClient\HttpBinding\LastRequestInfo;
use Canszr\SoapClient\HttpBinding\SoapRequest;
use Canszr\SoapClient\HttpBinding\SoapResponse;

class SoapClientHandler implements HandlerInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var LastRequestInfo
     */
    private $lastRequestInfo;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->lastRequestInfo = LastRequestInfo::createEmpty();
    }

    public function request(SoapRequest $request): SoapResponse
    {
        $response = $this->client->doActualRequest(
            $request->getRequest(),
            $request->getLocation(),
            $request->getAction(),
            $request->getVersion(),
            $request->getOneWay()
        );

        $this->lastRequestInfo = new LastRequestInfo(
            (string)$this->client->__getLastRequestHeaders(),
            (string)$this->client->__getLastRequest(),
            (string)$this->client->__getLastResponseHeaders(),
            (string)$this->client->__getLastResponse()
        );

        return new SoapResponse($response);
    }

    public function collectLastRequestInfo(): LastRequestInfo
    {
        return $this->lastRequestInfo;
    }
}