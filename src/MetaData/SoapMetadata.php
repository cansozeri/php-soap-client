<?php

namespace Canszr\SoapClient\MetaData;

use Canszr\SoapClient\Client;
use Canszr\SoapClient\MetaData\Collection\MethodCollection;
use Canszr\SoapClient\MetaData\Collection\TypeCollection;
use Canszr\SoapClient\MetaData\Collection\XsdTypeCollection;

/**
 * Class SoapMetadata
 * @package Canszr\SoapClient\MetaData
 * @link https://github.com/phpro/soap-client
 */
class SoapMetadata implements MetadataInterface
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var XsdTypeCollection|null
     */
    private $xsdTypes;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getMethods(): MethodCollection
    {
        return (new MethodsParser($this->getXsdTypes()))->parse($this->client);
    }

    public function getTypes(): TypeCollection
    {
        return (new TypesParser($this->getXsdTypes()))->parse($this->client);
    }

    private function getXsdTypes(): XsdTypeCollection
    {
        if (null === $this->xsdTypes) {
            $this->xsdTypes = XsdTypesParser::default()->parse($this->client);
        }

        return $this->xsdTypes;
    }

}