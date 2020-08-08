<?php

declare(strict_types=1);

namespace Canszr\SoapClient\MetaData\Model;

class Parameter
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var XsdType
     */
    private $type;

    public function __construct(string $name, XsdType $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): XsdType
    {
        return $this->type;
    }
}
