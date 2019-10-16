<?php


namespace App\ApiLogger;


use App\Entity\ApiLog;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class LogToFile implements LogToInterface
{
    private $filesystem;
    private $path;

    public function __construct(Filesystem $filesystem, ContainerBagInterface $params)
    {
        $this->filesystem = $filesystem;
        $this->path = $params->get('kernel.logs_dir') . '/' . 'api.log';
    }

    public function save(ApiLog $apiLog)
    {
        $this->filesystem->appendToFile($this->path, $apiLog . "\r\n\r\n");
    }

}