<?php

declare(strict_types=1);

namespace Canszr\SoapClient\MetaData\Visitor;

use Canszr\SoapClient\MetaData\Model\XsdType;

interface XsdTypeVisitorInterface
{
    public function __invoke(string $soapType): ?XsdType;
}
