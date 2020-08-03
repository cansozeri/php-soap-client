<?php

namespace Canszr\SoapClient\HttpBinding\Converter;

use Canszr\SoapClient\Exception\RequestException;
use Canszr\SoapClient\HttpBinding\Builder\Psr7RequestBuilder;
use Canszr\SoapClient\HttpBinding\SoapRequest;
use Canszr\SoapClient\HttpBinding\SoapResponse;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Class Psr7Converter
 */
class Psr7Converter
{
    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    public function __construct(RequestFactoryInterface $requestFactory, StreamFactoryInterface $streamFactory)
    {
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @param SoapRequest $request
     *
     * @throws RequestException
     * @return RequestInterface
     */
    public function convertSoapRequest(SoapRequest $request): RequestInterface
    {
        $builder = new Psr7RequestBuilder($this->requestFactory, $this->streamFactory);

        $request->isSOAP11() ? $builder->isSOAP11() : $builder->isSOAP12();
        $builder->setEndpoint($request->getLocation());
        $builder->setSoapAction($request->getAction());
        $builder->setSoapMessage($request->getRequest());

        return $builder->getHttpRequest();
    }

    /**
     * @param ResponseInterface $response
     *
     * @return SoapResponse
     */
    public function convertSoapResponse(ResponseInterface $response): SoapResponse
    {
        return new SoapResponse(
            (string) $response->getBody()
        );
    }
}
