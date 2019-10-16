<?php


namespace App\ApiLogger;

use App\Entity\ApiLog;

interface LogToInterface
{
    public function save(ApiLog $apiLog);
}