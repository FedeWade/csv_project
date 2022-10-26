<?php

namespace Federico\Bundle\CsvManagerBundle\Commands;

use Federico\Bundle\CsvManagerBundle\Processor\CsvProcessor;
use Federico\Bundle\CsvManagerBundle\Processor\ProcessorInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:print-csv',description: 'Print Csv file content')]
class PrintCsvCommand extends Command
{
    /**
     * @var ProcessorInterface|CsvProcessor
     */
    private ProcessorInterface $processor;

    /**
     * @param CsvProcessor $processor
     */
    public function __construct(CsvProcessor $processor)
    {
        $this->processor = $processor;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('path', InputArgument::REQUIRED, 'Enter the path to the file you want to read');
        $this->setHelp('This command allows you to print content of Csv file...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->processor->executeCommand($input->getArgument('path'), $output);
        return 1;
    }

}