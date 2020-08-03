<?php

namespace Canszr\SoapClient\Handler;

use Canszr\SoapClient\HttpBinding\LastRequestInfo;

interface LastRequestInfoCollectorInterface
{
    public function collectLastRequestInfo(): LastRequestInfo;
}