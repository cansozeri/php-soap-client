<?php

namespace Canszr\SoapClient\MetaData\Collection;

use Canszr\SoapClient\Exception\MetadataException;
use Canszr\SoapClient\MetaData\Model\Method;

class MethodCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Method[]
     */
    private $methods;

    public function __construct(Method ...$methods)
    {
        $this->methods = $methods;
    }

    /**
     * @return \ArrayIterator|Method[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->methods);
    }

    public function count(): int
    {
        return count($this->methods);
    }

    public function add(Method $method)
    {
        $this->methods[] = $method;
    }

    public function map(callable $callback): array
    {
        return array_map($callback, $this->methods);
    }

    public function fetchOneByName(string $name): Method
    {
        foreach ($this->methods as $method) {
            if ($name === $method->getName()) {
                return $method;
            }
        }

        throw MetadataException::methodNotFound($name);
    }
}