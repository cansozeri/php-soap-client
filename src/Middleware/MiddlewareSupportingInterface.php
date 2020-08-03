<?php

namespace Canszr\SoapClient\Middleware;

interface MiddlewareSupportingInterface
{
    /**
     * @param MiddlewareInterface $middleware
     *
     * @return void
     */
    public function addMiddleware(MiddlewareInterface $middleware);
}