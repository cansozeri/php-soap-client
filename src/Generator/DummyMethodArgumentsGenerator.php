<?php

namespace Canszr\SoapClient\Generator;

use Canszr\SoapClient\MetaData\MetadataInterface;

class DummyMethodArgumentsGenerator
{
    /**
     * @var MetadataInterface
     */
    private $metadata;

    public function __construct(MetadataInterface $metadata)
    {
        $this->metadata = $metadata;
    }

    public function generateForSoapCall(string $method): array
    {
        $methods = $this->metadata->getMethods();
        $method = $methods->fetchOneByName($method);

        return array_fill(0, \count($method->getParameters()), null);
    }
}