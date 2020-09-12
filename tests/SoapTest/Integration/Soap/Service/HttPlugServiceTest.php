<?php

declare(strict_types=1);

namespace CanszrTest\SoapClient\SoapTest\Integration\Soap\Service;

use Canszr\SoapClient\Handler\HttPlugHandle;
use Canszr\SoapClient\SoapEngine;
use Canszr\SoapClient\SoapOptions;
use Http\Adapter\Guzzle6\Client;

class HttPlugServiceTest extends AbstractServiceTest
{
    use SoapEngine;

    protected function skipLastHeadersCheck(): bool
    {
        return false;
    }

    /**
     * @param string $wsdl
     * @throws \Exception
     */
    protected function configureForWsdl(string $wsdl)
    {
        $options = SoapOptions::defaults($wsdl, [
            'soap_version' => SOAP_1_2,
        ])->disableWsdlCache();

        $handler = HttPlugHandle::createForClient(
            Client::createWithConfig(['headers' => ['User-Agent' => 'testing/1.0']])
        );

        $this->fromOptionsWithHandler($options, $handler);
    }
}