<?php

namespace Canszr\SoapClient;

use Canszr\SoapClient\Event\FaultEvent;
use Canszr\SoapClient\Event\RequestEvent;
use Canszr\SoapClient\Event\ResponseEvent;
use Canszr\SoapClient\Exception\SoapException;
use Canszr\SoapClient\Handler\HandlerInterface;
use Canszr\SoapClient\Handler\SoapClientHandler;
use Canszr\SoapClient\Service\SoapService;
use Canszr\SoapClient\Service\SoapServiceInterface;
use Canszr\SoapClient\Type\MixedResult;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;

trait SoapEngine
{
    /**
     * @var EngineDto
     */
    private $engineDto = null;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @param SoapOptions $options
     * @param EventDispatcher $dispatcher
     * @return SoapService
     * @throws \Exception
     */
    public function fromOptions(SoapOptions $options, EventDispatcher $dispatcher): SoapServiceInterface
    {
        $this->dispatcher = $dispatcher;
        $driver = SoapDriver::createFromOptions($options);
        $this->setEngineDto();
        $this->engineDto->setHandler(new SoapClientHandler($driver->getClient()));
        $this->engineDto->setService(new SoapService($driver, $this->getHandler()));

        return $this->getService();
    }

    /**
     * @param SoapOptions $options
     * @param HandlerInterface $handler
     * @param EventDispatcher $dispatcher
     * @return SoapService
     * @throws \Exception
     */
    public function fromOptionsWithHandler(
        SoapOptions $options,
        HandlerInterface $handler,
        EventDispatcher $dispatcher
    ): SoapServiceInterface {
        $this->dispatcher = $dispatcher;
        $driver = SoapDriver::createFromOptions($options);
        $this->setEngineDto();
        $this->engineDto->setHandler($handler);
        $this->engineDto->setService(new SoapService($driver, $this->getHandler()));

        return $this->getService();
    }

    protected function getService(): SoapServiceInterface
    {
        return $this->engineDto->getService();
    }

    protected function getHandler(): HandlerInterface
    {
        return $this->engineDto->getHandler();
    }

    protected function call(string $method, array $arguments)
    {
        $requestEvent = new RequestEvent($this->engineDto, $method, $arguments);
        $this->dispatcher->dispatch($requestEvent, RequestEvent::class);

        try {
            $result = $this->getService()->request($method, [$arguments]);
            $result = new MixedResult($result);
        } catch (\Exception $exception) {
            $soapException = SoapException::fromThrowable($exception);
            $this->dispatcher->dispatch(new FaultEvent($this->engineDto, $soapException, $requestEvent),
                FaultEvent::class);
            throw $soapException;
        }

        $this->dispatcher->dispatch(new ResponseEvent($this->engineDto, $method, $result), ResponseEvent::class);
        return $result;
    }

    private function setEngineDto(): void
    {
        if ($this->engineDto === null) {
            $this->engineDto = new EngineDto();
        }
    }
}