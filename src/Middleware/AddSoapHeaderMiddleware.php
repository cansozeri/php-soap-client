<?php

namespace Canszr\SoapClient\Middleware;

use Http\Promise\Promise;
use Canszr\SoapClient\Xml\SoapXml;
use Canszr\SoapClient\SoapHeaderDto;
use Psr\Http\Message\RequestInterface;
use Canszr\SoapClient\Middleware\Middleware;


class AddSoapHeaderMiddleware extends Middleware
{
    /**
     * @var SoapHeaderDto
     */
    private $header;

    public function __construct(SoapHeaderDto $header)
    {
        $this->header = $header;
    }

    public function getName(): string
    {
        return 'add_header_middleware';
    }

    public function beforeRequest(callable $handler, RequestInterface $request): Promise
    {
        $xml    = SoapXml::fromString((string)$request->getBody());
        $doc    = $xml->getXmlDocument();

        $xml->addEnvelopeNamespace('ns2', $this->header->getNameSpace());

        $header = $doc->createElement('SOAP-ENV:Header');
        $xml->prependSoapHeader($header);


        $headerName = $doc->createElement('ns2:' . $this->header->getHeaderName());
        $header->appendChild($headerName);

        foreach ($this->header->getHeader() as $key => $value) {
            $item  = $doc->createElement('item');
            $key   = $doc->createElement('key', $key);
            $value = $doc->createElement('value', $value);

            $headerName->appendChild($item);
            $item->appendChild($key);
            $item->appendChild($value);
        }

        $request = $request->withBody($xml->toStream());


        return $handler($request);
    }

}
