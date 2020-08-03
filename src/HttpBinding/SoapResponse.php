<?php

namespace Canszr\SoapClient\HttpBinding;

class SoapResponse
{
    /**
     * @var string
     */
    private $response;

    /**
     * SoapResponse constructor.
     *
     * @param string $response
     */
    public function __construct(string $response)
    {
        $this->response = $response;
    }

    /**
     * Get the full SOAP enveloppe response
     *
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }
}