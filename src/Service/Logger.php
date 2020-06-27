<?php


namespace App\Service;


use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;

class MarkdownHelper
{
    protected $logger;

    public function __construct(LoggerInterface $informationLogger)
    {
        $this->logger = $informationLogger;
    }

    public function parse(string $source):string
    {

        if (stripos($source, 'bacon') !== false) {
            $this->logger->info('there is a bacon word in there');

        }
    }
}