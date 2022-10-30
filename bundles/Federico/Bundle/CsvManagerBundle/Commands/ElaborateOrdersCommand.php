<?php

namespace Federico\Bundle\CsvManagerBundle\Commands;

use Federico\Bundle\CsvManagerBundle\Processors\ProcessorInterface;
use Federico\Bundle\CsvManagerBundle\Processors\WebServicesProcessor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:elaborate-orders', description: 'Print Csv file content')]
class ElaborateOrdersCommand extends Command
{
    /**
     * @var ProcessorInterface
     */
    private ProcessorInterface $processor;

    /**
     * @param WebServicesProcessor $processor
     */
    public function __construct(WebServicesProcessor $processor)
    {
        $this->processor = $processor;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('directoryPath', InputArgument::REQUIRED, 'Enter the path to the folder containing the orders');
        $this->setHelp('This command allows you to send orders to the correct office...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->processor->executeCommand($input->getArgument('directoryPath'), $output);
    }

}