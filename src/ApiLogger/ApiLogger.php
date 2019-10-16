<?php


namespace App\ApiLogger;


use App\Entity\ApiLog;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiLogger
{
    private $logTo;

    /**
     * ApiLogger constructor.
     *
     * @param ContainerBagInterface $params
     */
    public function __construct(LogToInterface $logTo)
    {
        $this->logTo = $logTo;
    }

    public function log(Request $request, Response $response)
    {
        $apiLog = new ApiLog();
        $apiLog->setMethod($request->getMethod());
        $apiLog->setEndpoint($request->getUri());
        $apiLog->setRequest($request->getContent());
        $apiLog->setIp($request->getClientIp());
        $apiLog->setResponseStatus($response->getStatusCode());
        $apiLog->setResponse($response->getContent());
        $apiLog->setTime(new \DateTime());

        $this->logTo->save($apiLog);
    }
}