<?php

namespace CanszrTest\SoapClient\SoapTest\Integration\Soap\Service;

use DOMNodeList;
use Canszr\SoapClient\Xml\SoapXml;
use PHPUnit\Framework\TestCase;

abstract class AbstractIntegrationTest extends TestCase
{
    abstract protected function configureForWsdl(string $wsdl);

    protected function runXpathOnBody(SoapXml $xml, string $xpath): DOMNodeList
    {
        $results = $xml->xpath($xpath, $xml->getBody());
        $this->assertGreaterThan(0, $results->length);

        return $results;
    }

    protected function runSingleElementXpathOnBody(SoapXml $xml, string $xpath): \DOMNode
    {
        $results = $xml->xpath($xpath, $xml->getBody());
        $this->assertEquals(1, $results->length);

        return $results->item(0);
    }
}