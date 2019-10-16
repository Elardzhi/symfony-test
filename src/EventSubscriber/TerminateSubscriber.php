<?php

namespace App\EventSubscriber;

use App\ApiLogger\ApiLogger;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use App\Entity\ApiLog;

class TerminateSubscriber implements EventSubscriberInterface
{
    private $apiLogger;

    public function __construct(ApiLogger $apiLogger)
    {
        $this->apiLogger = $apiLogger;
    }

    public static function getSubscribedEvents()
    {
        return [
            TerminateEvent::class => 'logApiData',
        ];
    }

    public function logApiData(TerminateEvent $event, $eventName, $dispatcher)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        $this->apiLogger->log($request, $response);




    }

}
