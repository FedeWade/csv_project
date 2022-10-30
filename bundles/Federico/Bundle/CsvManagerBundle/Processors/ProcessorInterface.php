<?php

namespace Federico\Bundle\CsvManagerBundle\Processors;

use Symfony\Component\Console\Output\OutputInterface;

interface ProcessorInterface
{
    public function executeCommand(string $directoryPath, OutputInterface $output): int;
}