<?php

namespace Canszr\SoapClient\Service;

use Canszr\SoapClient\Client;
use Canszr\SoapClient\Generator\DummyMethodArgumentsGenerator;
use Canszr\SoapClient\HttpBinding\SoapResponse;

class SoapDecoder implements DecoderInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var DummyMethodArgumentsGenerator
     */
    private $argumentsGenerator;

    public function __construct(Client $client, DummyMethodArgumentsGenerator $argumentsGenerator)
    {
        $this->client = $client;
        $this->argumentsGenerator = $argumentsGenerator;
    }

    public function decode(string $method, SoapResponse $response)
    {
        $this->client->registerResponse($response);
        try {
            $decoded = $this->client->__soapCall($method, $this->argumentsGenerator->generateForSoapCall($method));
        } finally {
            $this->client->cleanUpTemporaryState();
        }
        return $decoded;
    }
}