<?php

namespace Canszr\SoapClient\Xml;

use DOMDocument;

/**
 * Class WsdlXml
 *
 * @link https://github.com/phpro/soap-client
 */
class WsdlXml extends Xml
{
    /**
     * SoapXml constructor.
     *
     * @param DOMDocument $xml
     */
    public function __construct(DOMDocument $xml)
    {
        parent::__construct($xml);

        // Register some default namespaces for easy access:
        $this->registerNamespace('wsdl', $this->getWsdlNamespaceUri());
    }

    /**
     * @return string
     */
    public function getWsdlNamespaceUri(): string
    {
        return $this->getRootNamespace();
    }
}
