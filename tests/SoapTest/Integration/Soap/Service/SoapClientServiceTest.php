<?php

declare(strict_types=1);

namespace CanszrTest\SoapClient\SoapTest\Integration\Soap\Service;

use Canszr\SoapClient\SoapEngine;
use Canszr\SoapClient\SoapOptions;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SoapClientServiceTest extends AbstractServiceTest
{
    use SoapEngine;

    protected function skipLastHeadersCheck(): bool
    {
        return true;
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

        $eventDispatcher = new EventDispatcher();

        $this->fromOptions($options, $eventDispatcher);
    }
}