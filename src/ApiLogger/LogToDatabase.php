<?php


namespace App\ApiLogger;


use App\Entity\ApiLog;
use Doctrine\ORM\EntityManagerInterface;

class LogToDatabase implements LogToInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(ApiLog $apiLog)
    {
        $this->em->persist($apiLog);
        $this->em->flush();
    }
}