<?php

namespace Canszr\SoapClient;

use Canszr\SoapClient\Generator\DummyMethodArgumentsGenerator;
use Canszr\SoapClient\HttpBinding\SoapRequest;
use Canszr\SoapClient\HttpBinding\SoapResponse;
use Canszr\SoapClient\MetaData\LazyInMemoryMetadata;
use Canszr\SoapClient\MetaData\MetadataInterface;
use Canszr\SoapClient\MetaData\SoapMetadata;
use Canszr\SoapClient\Service\DecoderInterface;
use Canszr\SoapClient\Service\DriverInterface;
use Canszr\SoapClient\Service\EncoderInterface;
use Canszr\SoapClient\Service\SoapDecoder;
use Canszr\SoapClient\Service\SoapEncoder;

class SoapDriver implements DriverInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * @var MetadataInterface
     */
    private $metadata;

    public function __construct(
        Client $client,
        EncoderInterface $encoder,
        DecoderInterface $decoder,
        MetadataInterface $metadata
    ) {

        $this->client = $client;
        $this->encoder = $encoder;
        $this->decoder = $decoder;
        $this->metadata = $metadata;
    }

    /**
     * @param SoapOptions $options
     * @return static
     * @throws \Exception
     */
    public static function createFromOptions(SoapOptions $options): self
    {
        $client = Client::createFromOptions($options);

        return self::createFromClient($client);
    }

    public static function createFromClient(Client $client): self
    {
        $metadata = new LazyInMemoryMetadata(new SoapMetadata($client));

        return new self(
            $client,
            new SoapEncoder($client),
            new SoapDecoder($client, new DummyMethodArgumentsGenerator($metadata)),
            $metadata
        );
    }

    public function decode(string $method, SoapResponse $response)
    {
        return $this->decoder->decode($method, $response);
    }

    public function encode(string $method, array $arguments): SoapRequest
    {
        return $this->encoder->encode($method, $arguments);
    }

    public function getMetadata(): MetadataInterface
    {
        return $this->metadata;
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}