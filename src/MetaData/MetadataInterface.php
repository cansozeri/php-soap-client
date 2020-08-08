<?php

namespace Canszr\SoapClient\MetaData;

use Canszr\SoapClient\MetaData\Collection\MethodCollection;
use Canszr\SoapClient\MetaData\Collection\TypeCollection;

interface MetadataInterface
{
    public function getTypes(): TypeCollection;
    public function getMethods(): MethodCollection;
}