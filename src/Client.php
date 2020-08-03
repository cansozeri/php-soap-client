<?php

namespace Canszr\SoapClient;

use Canszr\SoapClient\HttpBinding\SoapRequest;
use Canszr\SoapClient\HttpBinding\SoapResponse;
use Canszr\SoapClient\TypeConverter\SoapOptionsResolverFactory;

class Client extends \SoapClient
{
    /**
     * @var SoapRequest|null
     */
    protected $storedRequest;

    /**
     * @var SoapResponse|null
     */
    protected $storedResponse;

    // @codingStandardsIgnoreStart
    /**
     * Internal SoapClient property for storing last request.
     *
     * @var string
     */
    protected $__last_request = '';
    // @codingStandardsIgnoreEnd

    // @codingStandardsIgnoreStart
    /**
     * Internal SoapClient property for storing last response.
     *
     * @var string
     */
    protected $__last_response = '';

    // @codingStandardsIgnoreEnd

    public function __construct($wsdl, array $options = [])
    {
        $options = SoapOptionsResolverFactory::createForWsdl($wsdl)->resolve($options);
        parent::__construct($wsdl, $options);
    }

    /**
     * @param SoapOptions $options
     * @return static
     * @throws \Exception
     */
    public static function createFromOptions(SoapOptions $options): self
    {
        try {
            return new self($options->getWsdl(), $options->getOptions());
        } catch (\SoapFault $e) {
            throw new \Exception('wsdl: ' . $options->getWsdl() . ' could not be load');
        }
    }

    public function __doRequest($request, $location, $action, $version, $oneWay = 0)
    {
        $this->storedRequest = new SoapRequest($request, $location, $action, $version, $oneWay);

        return $this->storedResponse ? $this->storedResponse->getResponse() : '';
    }

    public function doActualRequest(
        string $request,
        string $location,
        string $action,
        int $version,
        int $oneWay = 0
    ): string {
        $this->__last_request = $request;
        $this->__last_response = (string)parent::__doRequest($request, $location, $action, $version, $oneWay);

        return $this->__last_response;
    }

    public function collectRequest(): SoapRequest
    {
        if (!$this->storedRequest) {
            throw new \RuntimeException('No request has been registered yet.');
        }

        return $this->storedRequest;
    }

    public function registerResponse(SoapResponse $response)
    {
        $this->storedResponse = $response;
    }

    public function cleanUpTemporaryState()
    {
        $this->storedRequest = null;
        $this->storedResponse = null;
    }

    public function __getLastRequest(): string
    {
        return $this->__last_request;
    }

    public function __getLastResponse(): string
    {
        return $this->__last_response;
    }
}