<?php

namespace Canszr\SoapClient\Service;

use Canszr\SoapClient\HttpBinding\SoapResponse;

interface DecoderInterface
{
    /**
     * @param string $method
     * @param SoapResponse $response
     * @return mixed
     */
    public function decode(string $method, SoapResponse $response);
}