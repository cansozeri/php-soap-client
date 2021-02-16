<?php


namespace Canszr\SoapClient;

class SoapHeaderDto
{
    /** @var string */
    private $namespace;

    /** @var string */
    private $headerName;

    /** @var array */
    private $header;

    /** @var bool */
    private $mustUnderstandNS = false;


    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }


    /**
     * @param string $namespace
     * @return SoapHeaderDto
     */
    public function setNamespace(string $namespace): SoapHeaderDto
    {
        $this->namespace = $namespace;
        return $this;
    }


    /**
     * @return string
     */
    public function getHeaderName(): string
    {
        return $this->headerName;
    }

    /**
     * @param string $headerName
     * @return SoapHeaderDto
     */
    public function setHeaderName(string $headerName): SoapHeaderDto
    {
        $this->headerName = $headerName;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * @param array $header
     * @return SoapHeaderDto
     */
    public function setHeader(array $header): SoapHeaderDto
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMustUnderstandNS(): bool
    {
        return $this->mustUnderstandNS;
    }

    /**
     * @param bool $mustUnderstandNS
     * @return SoapHeaderDto
     */
    public function setMustUnderstandNS(bool $mustUnderstandNS): SoapHeaderDto
    {
        $this->mustUnderstandNS = $mustUnderstandNS;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'namespace'     => $this->getNameSpace(),
            'name'          => $this->getHeaderName(),
            'data'          => $this->getHeader(),
            'mustUnderStand'=> $this->isMustUnderstandNS()
        ];
    }
}
