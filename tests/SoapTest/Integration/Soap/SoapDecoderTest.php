<?php

declare(strict_types=1);

namespace CanszrTest\SoapClient\SoapTest\Integration\Soap;

use Canszr\SoapClient\Client;
use Canszr\SoapClient\Generator\DummyMethodArgumentsGenerator;
use Canszr\SoapClient\MetaData\SoapMetadata;
use Canszr\SoapClient\Service\DecoderInterface;
use Canszr\SoapClient\Service\SoapDecoder;
use Canszr\SoapClient\SoapOptions;
use CanszrTest\SoapClient\SoapTest\Integration\Soap\Service\AbstractDecoderTest;

class SoapDecoderTest extends AbstractDecoderTest
{
    /**
     * @var SoapDecoder
     */
    private $decoder;

    protected function getDecoder(): DecoderInterface
    {
        return $this->decoder;
    }

    /**
     * @param string $wsdl
     * @throws \Exception
     */
    protected function configureForWsdl(string $wsdl)
    {
        try {
            $this->decoder = new SoapDecoder(
                $client = Client::createFromOptions(
                    SoapOptions::defaults($wsdl, [])
                        ->disableWsdlCache()
                ),
                new DummyMethodArgumentsGenerator(new SoapMetadata($client))
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}