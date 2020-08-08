<?php

namespace CanszrTest\SoapClient\SoapTest\Integration\Soap;

use Canszr\SoapClient\Client;
use Canszr\SoapClient\Service\EncoderInterface;
use Canszr\SoapClient\Service\SoapEncoder;
use Canszr\SoapClient\SoapOptions;
use CanszrTest\SoapClient\SoapTest\Integration\Soap\Service\AbstractEncoderTest;

class SoapEncoderTest extends AbstractEncoderTest
{
    /**
     * @var SoapEncoder
     */
    private $encoder;

    protected function getEncoder(): EncoderInterface
    {
        return $this->encoder;
    }

    /**
     * @param string $wsdl
     * @throws \Exception
     */
    protected function configureForWsdl(string $wsdl)
    {
        try {
            $this->encoder = new SoapEncoder(
                $client = Client::createFromOptions(
                    SoapOptions::defaults($wsdl)
                        ->disableWsdlCache()
                )
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}