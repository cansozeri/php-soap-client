<?php

namespace Canszr\SoapClient\Service;

use Canszr\SoapClient\Handler\LastRequestInfoCollectorInterface;

interface SoapServiceInterface extends LastRequestInfoCollectorInterface
{
    public function request(string $method, array $arguments);
}