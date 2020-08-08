<?php

namespace Canszr\SoapClient\MetaData\Collection;

use Canszr\SoapClient\MetaData\Model\XsdType;

class XsdTypeCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var XsdType[]
     */
    private $types;

    public function __construct(XsdType ...$types)
    {
        $this->types = $types;
    }

    /**
     * @return \ArrayIterator|XsdType[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->types);
    }

    public function count(): int
    {
        return count($this->types);
    }

    public function add(XsdType $type)
    {
        $this->types[] = $type;
    }

    public function map(callable $callback): array
    {
        return array_map($callback, $this->types);
    }

    public function fetchByNameWithFallback(string $name): XsdType
    {
        foreach ($this->types as $type) {
            if ($name === $type->getName()) {
                return $type;
            }
        }

        return XsdType::guess($name);
    }
}