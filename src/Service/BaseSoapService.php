<?php

namespace Canszr\SoapClient\Service;

use Canszr\SoapClient\Exception\SoapException;
use Canszr\SoapClient\Handler\HttPlugHandle;
use Canszr\SoapClient\SoapDriver;
use Canszr\SoapClient\SoapOptions;
use Canszr\SoapClient\Type\MixedResult;
use Canszr\SoapClient\Type\ResultInterface;
use Canszr\SoapClient\Utils\XmlFormatter;
use Http\Adapter\Guzzle6\Client;

trait BaseSoapService
{
    /**
     * @var SoapServiceInterface
     */
    protected $service;

    /**
     * @param string $wsdl
     * @param array $options
     * @throws \Exception
     */
    protected function setSoapClient(string $wsdl, array $options = [])
    {
        try {

            $driver = SoapDriver::createFromOptions(
                SoapOptions::defaults($wsdl, $options)
                ->disableWsdlCache()
            );
            //$handler = new SoapClientHandler($driver->getClient());
            $handler = HttPlugHandle::createForClient(
                //Client::createWithConfig([])
            );

            $this->service = new SoapService($driver, $handler);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Make it possible to debug the last request.
     *
     * @return array
     */
    public function debugLastSoapRequest(): array
    {
        $lastRequestInfo = $this->service->collectLastRequestInfo();
        return [
            'request' => [
                'headers' => trim($lastRequestInfo->getLastRequestHeaders()),
                'body'    => XmlFormatter::format($lastRequestInfo->getLastRequest()),
            ],
            'response' => [
                'headers' => trim($lastRequestInfo->getLastResponseHeaders()),
                'body'    => XmlFormatter::format($lastRequestInfo->getLastResponse()),
            ]
        ];
    }

    /**
     * @param string $method
     * @param $arguments
     * @return ResultInterface
     */
    protected function call(string $method, $arguments): ResultInterface
    {
        try {
            $result = $this->service->request($method, $arguments);

            if (!$result instanceof ResultInterface) {
                $result = new MixedResult($result);
            }

        } catch (\Exception $e) {
            throw SoapException::fromThrowable($e);
        }

        return $result;
    }
}