<?php

namespace Canszr\SoapClient\Service;

use Canszr\SoapClient\HttpBinding\SoapRequest;

interface EncoderInterface
{
    public function encode(string $method, array $arguments): SoapRequest;
}