<?php

namespace Canszr\SoapClient\Service;

use Canszr\SoapClient\Client;
use Canszr\SoapClient\HttpBinding\SoapRequest;

class SoapEncoder implements EncoderInterface
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function encode(string $method, array $arguments): SoapRequest
    {
        try {
            if ($this->client->_soap_version == 1) {
                $arguments = array($arguments);
            }

            $this->client->__soapCall($method, $arguments);
            $encoded = $this->client->collectRequest();
        } finally {
            $this->client->cleanUpTemporaryState();
        }

        return $encoded;
    }
}