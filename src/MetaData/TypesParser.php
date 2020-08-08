<?php

namespace Canszr\SoapClient\MetaData;

use Canszr\SoapClient\Client;
use Canszr\SoapClient\MetaData\Collection\TypeCollection;
use Canszr\SoapClient\MetaData\Collection\XsdTypeCollection;
use Canszr\SoapClient\MetaData\Model\Property;
use Canszr\SoapClient\MetaData\Model\Type;
use Canszr\SoapClient\MetaData\Model\XsdType;

class TypesParser
{
    /**
     * @var XsdTypeCollection
     */
    private $xsdTypes;

    public function __construct(XsdTypeCollection $xsdTypes)
    {
        $this->xsdTypes = $xsdTypes;
    }

    public function parse(Client $client): TypeCollection
    {
        $collection = new TypeCollection();
        $soapTypes = $client->__getTypes();
        foreach ($soapTypes as $soapType) {
            $properties = [];
            $lines = explode("\n", $soapType);
            if (!preg_match('/struct (?P<typeName>.*) {/', $lines[0], $matches)) {
                continue;
            }
            $xsdType = XsdType::create($matches['typeName']);

            foreach (array_slice($lines, 1) as $line) {
                if ($line === '}') {
                    continue;
                }
                preg_match('/\s* (?P<propertyType>.*) (?P<propertyName>.*);/', $line, $matches);
                $properties[] = new Property(
                    $matches['propertyName'],
                    $this->xsdTypes->fetchByNameWithFallback($matches['propertyType'])
                );
            }

            $collection->add(new Type($xsdType, $properties));
        }

        return $collection;
    }
}