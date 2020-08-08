<?php

namespace Canszr\SoapClient\MetaData\Collection;

use Canszr\SoapClient\Exception\MetadataException;
use Canszr\SoapClient\MetaData\Model\Type;

class TypeCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Type[]
     */
    private $types;

    public function __construct(Type ...$types)
    {
        $this->types = $types;
    }

    /**
     * @return \ArrayIterator|Type[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->types);
    }

    public function count(): int
    {
        return count($this->types);
    }

    public function add(Type $type)
    {
        $this->types[] = $type;
    }

    public function addMany(TypeCollection $types)
    {
        foreach ($types as $type) {
            $this->add($type);
        }
    }

    public function map(callable $callback): array
    {
        return array_map($callback, $this->types);
    }

    public function fetchOneByName(string $name): Type
    {
        foreach ($this->types as $type) {
            if ($name === $type->getName()) {
                return $type;
            }
        }

        throw MetadataException::typeNotFound($name);
    }
}