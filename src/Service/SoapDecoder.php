<?php

namespace Canszr\SoapClient\Service;

use Canszr\SoapClient\Client;
use Canszr\SoapClient\HttpBinding\SoapResponse;

class SoapDecoder implements DecoderInterface
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function decode(string $method, SoapResponse $response)
    {
        $this->client->registerResponse($response);
        try {
            $decoded = $this->client->__soapCall($method, []);
        } finally {
            $this->client->cleanUpTemporaryState();
        }
        return $decoded;
    }
}