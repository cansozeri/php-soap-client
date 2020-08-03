<?php

namespace Canszr\SoapClient\Handler;

use Canszr\SoapClient\HttpBinding\SoapRequest;
use Canszr\SoapClient\HttpBinding\SoapResponse;

interface HandlerInterface extends LastRequestInfoCollectorInterface
{
    public function request(SoapRequest $request): SoapResponse;
}