<?php

namespace Canszr\SoapClient\Event\Subscriber;

use Canszr\SoapClient\Event\FaultEvent;
use Canszr\SoapClient\Event\RequestEvent;
use Canszr\SoapClient\Event\ResponseEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LogSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onClientRequest(RequestEvent $event)
    {
        $this->logger->info(sprintf(
            '[soap-client] request: call "%s" with params %s',
            $event->getMethod(),
            json_encode($event->getArguments())
        ));
    }

    public function onClientResponse(ResponseEvent $event)
    {
        $this->logger->info(sprintf(
            '[soap-client] response: %s',
            json_encode($event->getResponse())
        ));
    }

    public function onClientFault(FaultEvent $event)
    {
        $this->logger->error(sprintf(
            '[soap-client] fault "%s" for request "%s" with params %s',
            $event->getSoapException()->getMessage(),
            $event->getRequestEvent()->getMethod(),
            json_encode($event->getRequestEvent()->getArguments())
        ));
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onClientRequest',
            ResponseEvent::class => 'onClientResponse',
            FaultEvent::class => 'onClientFault'
        ];
    }
}