<?php

namespace Federico\Bundle\CsvManagerBundle\Processor;

use Symfony\Component\Console\Output\OutputInterface;

interface ProcessorInterface
{
    public function executeCommand(string $path, OutputInterface $output);
}