<?php

namespace Canszr\SoapClient\MetaData;

use Canszr\SoapClient\Client;
use Canszr\SoapClient\MetaData\Collection\XsdTypeCollection;
use Canszr\SoapClient\MetaData\Model\XsdType;
use Canszr\SoapClient\MetaData\Visitor\ListVisitor;
use Canszr\SoapClient\MetaData\Visitor\SimpleTypeVisitor;
use Canszr\SoapClient\MetaData\Visitor\UnionVisitor;
use Canszr\SoapClient\MetaData\Visitor\XsdTypeVisitorInterface;

class XsdTypesParser
{
    /**
     * @var XsdTypeVisitorInterface[]
     */
    private $visitors;

    public function __construct(XsdTypeVisitorInterface ...$visitors)
    {
        $this->visitors = $visitors;
    }

    public static function default(): self
    {
        return new self(
            new ListVisitor(),
            new UnionVisitor(),
            new SimpleTypeVisitor()
        );
    }

    public function parse(Client $client): XsdTypeCollection
    {
        $collection = new XsdTypeCollection();
        $soapTypes = $client->__getTypes();
        foreach ($soapTypes as $soapType) {
            if ($type = $this->detectXsdType($soapType)) {
                $collection->add($type);
            }
        }

        return $collection;
    }

    private function detectXsdType(string $soapType): ?XsdType
    {
        foreach ($this->visitors as $visitor) {
            if ($type = $visitor($soapType)) {
                return $type;
            }
        }

        return null;
    }
}